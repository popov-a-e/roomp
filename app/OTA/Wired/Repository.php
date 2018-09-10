<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 02.08.2017
 * Time: 15:07
 */

namespace Roomp\OTA\Wired;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Roomp\Events\WubookConnectionBroken;
use Roomp\RatePlan;
use Roomp\Repositories\HotelRepository;
use Roomp\Repositories\ReservationRepository;
use Roomp\Repositories\UserRepository;
use Roomp\Drivers\OTA\Wubook\Rate;
use Roomp\Drivers\OTA\Wubook\Account;
use Roomp\Drivers\OTA\Wubook\Restriction;

class Repository {
  const RTYPE_ROOM = 1;
  const RTYPE_APARTMENT = 2;

  private $hotelRepository;
  private $driver;
  private $roompRoom;
  private $reservationRepository;
  private $userRepository;


  public function __construct(/*HotelRepository $hotelRepository,
                              Driver $driver,
                              ReservationRepository $reservationRepository,
                              UserRepository $userRepository,
                              Room $room*/) {/*
    $this->hotelRepository = $hotelRepository;
    $this->driver = $driver;
    $this->reservationRepository = $reservationRepository;
    $this->roompRoom = $room;
    $this->userRepository = $userRepository;*/
  }

  public function getWubookRoomID(int $roompRoomID) {
    $room = Room::where('room_id', $roompRoomID)->where('disabled', false)->first();

    if (!$room) {
      throw new ModelNotFoundException("Room not found on WuBook", 404);
    }

    return $room->rid;
  }

  public function getRoom(int $roompRoomID) {
    return Room::where('room_id', $roompRoomID)->where('disabled', false)->first();
  }

  /*
   * @deprecated
   */

  public function getRoompHotel(int $lcode) {
    $hotel = Hotel::where('lcode', $lcode)->first();

    if (!$hotel) {
      throw new ModelNotFoundException("Hotel not found on WuBook", 404);
    }

    return $hotel;
  }

  public function getRoompRoom(int $rid) {
    $room = Room::where('rid', $rid)->first();

    if (!$room) {
      throw new ModelNotFoundException("Room not found on WuBook", 404);
    }

    return $room;
  }

  public function getRoompRoomID(int $rid) {
    return $this->getRoompRoom($rid)->room_id;
  }

  public function getRoompHotelID(int $lcode) {
    return $this->getRoompHotel($lcode)->hotel_id;
  }

  public function getHotelID(int $roompHotelID) {
    $hotel = $this->getHotel($roompHotelID);

    return $hotel->lcode;
  }

  private function increment($code) {
    $arr = [
      $code[0], $code[1], $code[2], $code[3]
    ];

    $normalize = function ($chr) {
      return ord($chr) - 65;
    };

    $count = 0;

    $number = array_reduce($arr, function ($total, $r) use ($normalize, &$count) {
      $count++;
      return $total + $normalize($r) * pow(26, 4 - $count);
    }, 0);

    $number++;

    $code = '';
    for ($i = 0; $i < 4; ++$i) {
      $code = chr(65 + $number % 26) . $code;
      $number /= 26;
    }

    return $code;
  }

  public function transferRooms(int $roompHotelID) {
    $lastCode = Room::orderBy('id', 'desc')->first();
    $lastCode = $lastCode ? $lastCode->code : 'FFFF';

    $connection = Connection::where('hotel_id', $roompHotelID)->first();
    $rooms = $this->hotelRepository->find($roompHotelID)->rooms;

    return $rooms->map(function ($room) use (&$lastCode, $connection) {
      $lastCode = $this->increment($lastCode);

      return [
        'woodoo' => 0,
        'name' => $room->class->en . ", " . $room->allotments->map(function ($a) {
            return $a->en;
          })->implode(","),
        'shortname' => $lastCode,
        'defboard' => 'nb',
        'lcode' => $connection->lcode,
        'rtype' => self::RTYPE_ROOM,//TODO
        'avail' => 0,
        'beds' => $room->max_guest_number,
        'defprice' => 100000,
        'id' => $room->id
      ];
    })->values();
  }

  public function deleteRooms($hotelID) {
    $this->hotelRepository->find($hotelID)->rooms()->with('wubook_room')->get()->each(function ($room) {
      if ($room->wubook_room) {
        $room->wubook_room->delete();
      }
    });
  }

  public function allocateRoom(int $roompRoomID, string $code, int $lcode, int $rid) {
    $room = new Room;

    $room->rid = $rid;
    $room->code = $code;
    $room->room_id = $roompRoomID;
    $room->lcode = $lcode;

    $room->save();

    return true;
  }

  public function allocateHotel(int $roompHotelID, int $lcode) {
    $hotel = Hotel::where('lcode', $lcode)->first();

    if (!$hotel) {
      $hotel = new Hotel;
    }

    $hotel->lcode = $lcode;
    $hotel->hotel_id = $roompHotelID;

    $hotel->save();

    return $hotel;
  }

  private function getDateFromWubookFormat(string $date) {
    $pieces = explode('/', $date);

    return "{$pieces[2]}-{$pieces[1]}-{$pieces[0]}";
  }

  public function handleReservation($lcode, $rcode) {
    $reservation = $this->driver->getReservation($lcode, $rcode);
    if (is_string($reservation) || empty($reservation)) {
      throw new \Exception($reservation);
    }

    $rdata = json_encode($reservation[0]);

    file_put_contents('/var/log/roomp/wubook_data.log', "$lcode, $rcode: $rdata \r\n", FILE_APPEND);

    function array_to_object($array) {
      $obj = new \stdClass;
      foreach ($array as $k => $v) {
        if (strlen($k)) {
          if (is_array($v)) {
            $obj->{$k} = array_to_object($v);
          } else {
            $obj->{$k} = $v;
          }
        }
      }
      return $obj;
    }

    $reservation = array_to_object($reservation[0]);

    if (!isset($reservation->deleted_at)) {
      if ($this->getReservation($lcode, $rcode)) {
        return true;
      }

      $res = $this->book($reservation, $lcode);
      $this->storeReservation($lcode, $rcode, $res->id, true);
    } else {
      $res = $this->cancel($lcode, $rcode);
      $this->storeReservation($lcode, $rcode, $res->id, false);
    }
  }

  private function storeReservation($lcode, $rcode, $reservationID, $active) {
    $reservation = $this->getReservation($lcode, $rcode);
    if (!$reservation) {
      $reservation = new Reservation;

      $reservation->lcode = $lcode;
      $reservation->rcode = $rcode;
      $reservation->reservation_id = $reservationID;
      $reservation->active = $active;

      $reservation->save();
    }

    return $reservation;
  }

  public function getReservation($lcode, $rcode) {
    return Reservation::where('rcode', $rcode)->where('lcode', $lcode)->first();
  }

  public function getRoompReservation($reservationID) {
    return Reservation::where('reservation_id', $reservationID)->first();
  }

  public function cancel($lcode, $rcode) {
    $reservation = $this->getReservation($lcode, $rcode);

    $reservation->reservation->cancel();

    return $reservation->reservation;
  }

  public function book($reservation, $lcode) {
    $from = $this->getDateFromWubookFormat($reservation->date_arrival);
    $till = $this->getDateFromWubookFormat($reservation->date_departure);

    $nights = (strtotime($till) - strtotime($from)) / 86400;

    $hotelID = 0;

    $children = $reservation->children;
    $men = $reservation->men;

    $comment = isset($reservation->customer_notes) ? $reservation->customer_notes : '';

    $allotmentPreference = 0;

    if (preg_match('|BED PREFERENCE:.+1 double|im', $comment)) $allotmentPreference = 1;
    if (preg_match('|BED PREFERENCE:.+2 singles|im', $comment)) $allotmentPreference = 2;

    $isBreakfast = $this->isBreakfastIncluded($reservation->ancillary);

    $ancillaryRooms = [];

    foreach ($reservation->ancillary as $key => $value)
      if (preg_match('|^Room\s.+|im', $key))
        $ancillaryRooms[] = $value;

    $occupancies = collect($reservation->booked_rooms)->map(function ($room, $i) use (&$children, &$men, &$hotelID, $allotmentPreference, &$ancillaryRooms,$lcode) {
      $ancillaryRoom = $ancillaryRooms[$i] ?? null;

      $dailyRatesKey = 'Daily rates';
      $firstIndex = '0';

      $rate = $this->getRateByID($lcode, $room->roomdays->$firstIndex->rate_id ?? null);

      $tariff = $ancillaryRoom->$dailyRatesKey->$firstIndex ?? '';

      $tariff = preg_match('/.+\((.+)\)/', $tariff, $matches);

      $tariff = $matches[1] ?? $matches;

      if ($rate) {
        $isBreakfast = $rate->breakfast;
        $isNR = $rate->rate_id === 2;
      } else {
        $isBreakfast = ($ancillaryRoom->Meal ?? '') === 'Завтрак входит в стоимость номера.';
        $isNR = !!preg_match('/( NR|Non-Ref|non-ref|Non-ref)/', $tariff);
      }

      $wubookRoom = $this->getRoompRoom($room->room_id);
      $roompRoom = $wubookRoom->room;
      $hotelID = $roompRoom->hotel_id;

      $_men = $room->ancillary->guests ?? 2;
      $_children = 0;

      if ($children > 0) {
        $_children = min($children, $_men - 1);
        $children -= $_children;
        $_men = $_men - $_children;
      }

      $prices = collect($room->roomdays)->pluck('price');

      return [
        'prices' => $prices->toArray(),
        'adults' => $_men,
        'children' => $_children,
        'infants' => 0,
        'room_id' => $roompRoom->id,
        'allotment' => $allotmentPreference ?: ($roompRoom->allotments->first()->id ?? 1),
        'tariff' => $tariff,
        'is_nr' => $isNR,
        'breakfast_included' => $isBreakfast
      ];
    })->toArray();

    $user = $this->getOrRegisterUser($reservation);

    $isNR = $this->isNR($reservation->ancillary);
    $online = $this->isOnline($reservation->ancillary) || !!$reservation->payment_gateway_fee;

    $reservation = $this->reservationRepository->create([
      'hotel_id' => $hotelID,
      'from' => $from,
      'till' => $till,
      'occupancies' => $occupancies,
      'guest_name' => $this->getGuestName($reservation),
      'nights' => $nights,
      'online' => $online,
      'comment' => $comment,
      'tarriff' => $isNR ? 'NR' : null,
      'breakfast' => $isBreakfast
    ], $user);

    return $reservation;
  }

  private function getGuestName($reservation) {
    foreach ($reservation->ancillary as $room) {
      if (isset($room->Guest)) return $room->Guest;
    }

    return $reservation->customer_name.' '.$reservation->customer_surname;
  }

  private function decodeGuestName($value) {
    $originalString = json_decode(json_encode($value));
    $str = preg_replace("/\\\\u([0-9abcdef]{4})/", "&#x$1;", $originalString);
    $name = mb_convert_encoding($str, 'UTF-8', 'HTML-ENTITIES');
    return iconv(iconv_get_encoding($name), 'utf-8//IGNORE', $name);
  }

  private function isNR($room) {
    //foreach ($ancillary as $key => $value) {
      //if (preg_match('|^Room\s.+|im', $key)) {
        $v = ((array)$room);

        if (!isset($v['Daily rates'])) return false;
        $v = (array)$v['Daily rates'];
        if (preg_match('|NR |', $v[0] ?? '')) {
          return true;
        }
      //}
    //}

    return false;
  }

  private function isBreakfastIncluded($ancillary) {

    foreach ($ancillary as $key => $value) {
      if (preg_match('|^Room\s.+|im', $key)) {
        if ($value->Meal === 'Завтрак входит в стоимость номера.') return true;
      }
    }

    return false;
  }

  private function isOnline($ancillary) {
    foreach ($ancillary as $key => $value) {
      if ($key === "Payment") return true;
    }

    return false;
  }

  public function getOrRegisterUser($reservation) {
    if (isset($reservation->customer_mail) && isset($reservation->customer_phone)) {
      $phone = preg_replace('|\D|im', '', $reservation->customer_phone);
      $email = $reservation->customer_mail;
      $name = $this->getGuestName($reservation);

      return $this->userRepository->getOrRegister(compact('phone', 'email', 'name'));
    }

    return false;
  }

  public function getConnection($hotelID, $full = false) {
    if ($full) {
      $connection = Connection::with('mappings', 'rates')->where('hotel_id', $hotelID)->first();
    } else {
      $connection = Connection::where('hotel_id', $hotelID)->first();

      return $connection;
    }

    if (!$connection) {
      throw new ModelNotFoundException('Connection not found');
    }

    $active = collect($this->driver->getOTAs($connection->lcode))->where('id', $connection->chid)->first()['running'] ?? false;

    if ($connection->last_active && !$active) {
      event(new WubookConnectionBroken($connection->hotel_id));
    }

    $connection->last_active = $active ? Carbon::now() : null;

    $connection->save();

    return $connection;
  }

  public function getConnectionChID($hotelID) {
    return $this->getConnection($hotelID)->chid;
  }

  public function createConnection($hotelID, $lcode, $chid) {
    $conn = new Connection();

    $conn->hotel_id = $hotelID;
    $conn->lcode = $lcode;
    $conn->chid = $chid;

    return $conn->save();
  }

  public function assignBookingHotel($hotelID, $bookingHotelID) {
    return Connection::where('hotel_id', $hotelID)->update(['booking_hotel_id' => $bookingHotelID]);
  }

  public function flushMappings($chid) {
    RateMapping::where('chid', $chid)->delete();
    ConnectionMapping::where('chid', $chid)->delete();
  }

  public function createMapping($rooms, $chid) {
    foreach ($rooms as $booking_room_id => $roomData) {
      $mapping = ConnectionMapping::where('booking_room_id', $booking_room_id)->where('chid', $chid)->first();

      if (!$mapping) {
        $mapping = new ConnectionMapping();
      }

      $mapping->booking_room_id = $booking_room_id;
      $mapping->booking_name = $roomData['name'];
      $mapping->chid = $chid;

      $mapping->save();
    }
  }

  public function createRates($rates, $chid) {
    foreach ($rates as $booking_rate_id => $rateData) {
      $rate = RateMapping::where('booking_rate_id', $booking_rate_id)->where('chid', $chid)->first();

      if (!$rate) {
        $rate = new RateMapping();
      }

      $rate->booking_rate_id = $booking_rate_id;
      $rate->booking_name = $rateData['name'];
      $rate->chid = $chid;

      $rate->save();
    }
  }

  public function setRateMapping($mapping) {
    foreach ($mapping as $booking_rate_id => $data) {
      $m = RateMapping::where('booking_rate_id', $booking_rate_id)->first();

      $m->wubook_rate_id = $data['pplan'];
      $m->wubook_rate_plan_id = $data['rplan'];

      $m->save();
    }
  }

  public function setMapping($mapping) {
    foreach ($mapping as $booking_room_id => $wubook_id) {
      $m = ConnectionMapping::where('booking_room_id', $booking_room_id)->first();

      $m->wubook_room_id = $wubook_id;

      $m->save();
    }
  }

  public function setChannelConfirmed($hotelID) {
    return Connection::where('hotel_id', $hotelID)->update(['channel_confirmed' => true]);
  }

  public function getRooms($hotelID) {
    return $this->hotelRepository->find($hotelID)->rooms()->with('class', 'allotments', 'wubook_room')->has('wubook_room')->get();
  }

  public function getRates($hotelID) {
    $connection = Connection::where('hotel_id', $hotelID)->first();
    $arr = Rate::where('lcode', $connection->lcode)->get();

    $n = $this->hotelRepository->find($hotelID)->rooms()->get([DB::raw('MAX(max_guest_number) as max')])->pluck('max')->first();

    $arr = $arr->filter(function ($el) use ($n) {
      return $el->occupancy <= $n;
    });


    return $arr;
  }

  public function getRatePlans($hotelID) {
    $connection = Connection::where('hotel_id', $hotelID)->first();
    $arr = Restriction::where('lcode', $connection->lcode)->get();

    return RatePlan::all()->map(function ($rplan) use ($arr) {

      $rplan->wubook_id = $arr->where('rate_id', $rplan->id)->first()->rplan_id ?? null;

      return $rplan;
    })->where('wubook_id', '<>', null);
  }

  public function getRate($lcode, $rateID, $occupancy, $breakfast = false) {
    return Rate::where('lcode', $lcode)->where('rate_id', $rateID)->where('occupancy', $occupancy)->where('breakfast', $breakfast)->first();
  }

  public function getRateByID($lcode, $rateID) {
    return Rate::where('lcode', $lcode)->where('rid', $rateID)->first();
  }

  public function getRatePlan($roompRatePlanID) {
    return 1; // Rate::where('
  }

  public function getRatePlanID($lcode, $id) {
    return Restriction::where('lcode', $lcode)->where('rate_id', $id)->first()->rplan_id ?? false;
  }

  public function getRoompRatePlanID($lcode, $id) {
    return array_search($id, Restriction::where('lcode', $lcode)->get());
  }

  public function getAvailability($hotelID, $from, $till) {
    $hotel = $this->hotelRepository->find($hotelID);

    $avail = $hotel->availabilityArray()->where('date', '>=', $from)->where('date', '<=', $till)->orderBy('date')->get();

    $avaiability = [];

    $hotel->rooms->each(function ($room) use (&$avaiability, $avail) {
      $avaiability[$room->id] = $avail->where('room_id', $room->id)->map(function ($el) {
        return [
          'date' => $el->date,
          'available' => $el->available
        ];
      })->toArray();
    });

    return $avaiability;
  }
}