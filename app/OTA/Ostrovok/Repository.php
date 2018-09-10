<?php

namespace Roomp\OTA\Ostrovok;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Roomp\Repositories\HotelRepository;
use Roomp\Repositories\ReservationRepository;
use Roomp\Room as RoompRoom;

class Repository {
  private $driver;
  private $repository;
  private $hotelRepository;

  public function __construct(Driver $driver/*, ReservationRepository $repository, HotelRepository $hotelRepository*/) {
    $this->driver = $driver;/*
    $this->repository = $repository;
    $this->hotelRepository = $hotelRepository;*/
  }

  public function getHotel($id, $withRooms = true) {
    if ($withRooms) {
      $hotel = Hotel::with('rooms.occupancies', 'rooms.rates')->where('ostrovok_id', $id)->first();
    } else {
      $hotel = Hotel::where('ostrovok_id', $id)->first();
    }

    if (!$hotel) {
      throw new ModelNotFoundException("Hotel with id $id not found", 404);
    }

    return $hotel;
  }

  public function getHotels() {
    return Hotel::get(['*', 'ostrovok_id as id']);
  }

  public function getRooms($hotelID) {
    return Hotel::where('ostrovok_id', $hotelID)->first()->rooms()->get(['*', 'ostrovok_id as id']);
  }

  public function getOstrovokHotel($id, $withRooms = true) {
    if ($withRooms) {
      $hotel = Hotel::with('rooms.occupancies', 'rooms.rates')->where('hotel_id', $id)->first();
    } else {
      $hotel = Hotel::where('hotel_id', $id)->first();
    }

    if (!$hotel) {
      throw new ModelNotFoundException("Hotel with id $id not found", 404);
    }

    return $hotel;
  }

  public function getRoom($id, $withDependencies = true) {
    if ($withDependencies) {
      $room = Room::with('occupancies', 'rates')->where('ostrovok_id', $id)->first();
    } else {
      $room = Room::where('ostrovok_id', $id)->first();
    }

    if (!$room) {
      throw new ModelNotFoundException("Room with id $id not found", 400);
    }

    return $room;
  }

  public function getRoompRoomID($ostrovokRoomID) {
    $roomID = $this->getRoom($ostrovokRoomID, false)->room_id;

    if (!$roomID) {
      throw new RelationNotFoundException("No related room for ostrovok room with id $ostrovokRoomID");
    }

    return $roomID;
  }

  public function getRoompHotelID($ostrovokHotelID) {
    $hotelID = $this->getHotel($ostrovokHotelID, false)->hotel_id;

    if (!$hotelID) {
      throw new RelationNotFoundException("No related hotel for ostrovok hotel with id $ostrovokHotelID");
    }

    return $hotelID;
  }

  public function updateHotelData($hotelID) {
    $rooms = $this->driver->getRooms($hotelID);
    $occupancies = $this->driver->getOccupancies($hotelID);
    $rates = $this->driver->getRatePlans($hotelID);

    //$this->resetHotelData($hotelID);

    foreach ($rooms as $room) {
      $this->checkRoom($room);
    }

    foreach ($occupancies as $occupancy) {
      $this->checkOccupancy($occupancy);
    }

    foreach ($rates as $rate) {
      $this->checkRate($rate);
    }
  }

  public function resetHotelData($hotelID) {
    $this->getHotel($hotelID)->rooms->each(function ($room) {
      $room->delete();
    });
  }

  public function associateRoom($ostrovokRoomID, $roomID) {
    Room::where('room_id', $roomID)->update(['room_id' => null]);

    Room::where('ostrovok_id', $ostrovokRoomID)->update(['room_id' => $roomID]);
  }

  public function associateHotel($ostrovokHotelID, $hotelID) {
    Hotel::where('hotel_id', $hotelID)->update(['hotel_id' => null]);

    Hotel::where('ostrovok_id', $ostrovokHotelID)->update(['hotel_id' => $hotelID]);
  }

  public function getOccupanciesArray($data) {
    $room = Room::with('rates', 'occupancies')->where('room_id', $data['room_id'])->first();

    if (!$room) {
      return false;
    }

    $occupancy = $room->occupancies->where('capacity', $data['occupancy'])->first();

    $rate = $room->rates->first();

    //TODO: bind roomp rate_plans to ostrovok rates

    return  [
      'plan_date_start_at' => $data['from'],
      'plan_date_end_at' => $data['till'],
      'room_category' => $room->ostrovok_id,
      'price' => $data['price'],
      'rate_plan' => $rate->ostrovok_id,
      'occupancy' => $occupancy->ostrovok_id
    ];
  }

  public function transformRestrictionArray($restriction) {
    $plan = [];

    $room = Room::where('room_id', $restriction->room_id)->first();

    $plan['plan_date_start_at'] = $restriction->from;
    $plan['plan_date_end_at'] = $restriction->till;
    $plan['room_category'] = $room->ostrovok_id;
    $plan['rate_plan'] = $room->rates->first()->ostrovok_id;

    foreach([
              ['disable_flexible' => 'closed'],
              ['closed_on_arrival' => 'closed_to_arrival'],
              ['closed_on_departure' => 'closed_to_departure'],
              ['min_stay_through' => 'minstay'],
              ['max_stay_through' => 'maxstay'],
              ['min_stay_arrival' => 'minstay_on_arrival']
            ] as $key => $value) {
      foreach ($value as $ostrovok => $roomp) {
        if (isset($restriction->params[$roomp])) {
          $plan[$ostrovok] = $key < 3 ? !!$restriction->params[$roomp] : $restriction->params[$roomp];
        }
      }
    }

    return $plan;
  }

  public function getRoomCategoriesArray(int $roomID, array $availability): array {
    $room = Room::where('room_id', $roomID)->first();

    if (!$room) {
      return [];
      abort(400, "No ostrovok link for room_id=$roomID");
    }

    $room_categories = [];

    foreach ($availability as $point) {
      array_push($room_categories, [
        'plan_date_start_at' => $point['date'],
        'plan_date_end_at' => $point['date'],
        'room_category' => $room->ostrovok_id,
        'count' => $point['available']
      ]);
    }

    return $room_categories;
  }

  public function checkHotel($ostrovokHotel): void {
    $hotel = Hotel::where('ostrovok_id', $ostrovokHotel->id)->first();

    if (!$hotel) {
      $hotel = new Hotel;
    }

    if ($hotel->name !== $ostrovokHotel->name) {
      $hotel->ostrovok_id = $ostrovokHotel->id;
      $hotel->name = $ostrovokHotel->name;

      $hotel->save();
    }
  }

  private function checkRoom($ostrovokRoom): void {
    $room = Room::where('ostrovok_id', $ostrovokRoom->id)->first();

    if (!$room) {
      $room = new Room;
    }

    if ($room->name !== $ostrovokRoom->name) {
      $room->ostrovok_id = $ostrovokRoom->id;
      $room->ostrovok_hotel_id = $ostrovokRoom->hotel;
      $room->name = $ostrovokRoom->name;

      $room->save();
    }
  }

  private function checkOccupancy($ostrovokOccupancy): void {
    if (Occupancy::where('ostrovok_id', $ostrovokOccupancy->id)->count() === 0) {
      $occupancy = new Occupancy;

      $occupancy->ostrovok_id = $ostrovokOccupancy->id;
      $occupancy->capacity = $ostrovokOccupancy->capacity;
      $occupancy->ostrovok_room_id = $ostrovokOccupancy->room_category;

      $occupancy->save();
    }
  }

  private function checkRate($ostrovokRate): void {
    if (Rate::where('ostrovok_id', $ostrovokRate->id)->count() === 0) {
      $rate = new Rate;

      $rate->ostrovok_id = $ostrovokRate->id;
      $rate->name = $ostrovokRate->name;
      $rate->ostrovok_room_id = $ostrovokRate->room_category;

      $rate->save();
    }
  }

  public function getReservation(int $id, string $uuid) {
    return Reservation::where('ostrovok_id', $id)->orWhere('ostrovok_id', $uuid)->first();
  }

  public function getOstrovokReservation(int $id) {
    return Reservation::where('reservation_id', $id)->first();
  }

  public function storeReservation(string $id, int $reservationID, int $hotelID, string $action) {
    $reservation = new Reservation;

    $reservation->ostrovok_id = $id;
    $reservation->reservation_id = $reservationID;
    $reservation->ostrovok_hotel_id = $hotelID;
    $reservation->action = $action;

    $reservation->save();

    return $reservation;
  }

  public function getRoompReservation(int $reservationID) {
    return Reservation::where('reservation_id', $reservationID)->first();
  }

  public function handleReservation($requestData) {
    $reservation = $this->getReservation($requestData->id, $requestData->uuid);

    if ($requestData->action_description === 'created') {
      if (!$reservation) {
        try {
          $this->book($requestData);
        } catch (\Exception $e) {
          return false;
        }
      } else {
        return true;
      }
    } else if ($requestData->action_description === 'cancelled') {
      if (!$reservation || $reservation->action === 'cancelled') {
        return true;
      }
      try {
        $this->cancel($reservation->reservation_id);
      } catch (\Exception $e) {
        return true;
      }
    } else {
      if ($requestData->action_description === 'missed' && $reservation) {
        $this->modify($reservation, $requestData);
      }
    }

    return true;
  }

  private function book($data) {
    $hotelID = $this->getRoompHotelID($data->hotel);
    $roomID = $this->getRoompRoomID($data->room_category);
    $guestName = $data->first_name . ' ' . $data->last_name;
    $adults = $data->adults;
    $children = 0;
    $infants = 0;

    collect($data->children)->each(function ($el) use (&$children, &$infants) {
      if ($el < 2) $infants++;
      else $children++;
    });

    $from = Carbon::parse($data->arrive_at);
    $till = Carbon::parse($data->depart_at);
    $prices = $data->price_per_day;
    $nights = $from->diffInDays($till);
    $online = (bool)$data->is_pre_pay;
    $allotment = $this->getAllotment($data, $roomID);

    $reservation = $this->repository->create([
      'hotel_id' => $hotelID,
      'from' => $from,
      'till' => $till,
      'occupancies' => [
        [
          'room_id' => $roomID,
          'adults' => $adults,
          'children' => $children,
          'infants' => $infants,
          'prices' => $prices,
          'allotment' => $allotment
        ]
      ],
      'guest_name' => $guestName,
      'nights' => $nights,
      'online' => $online,
      'comment' => $data->comment
    ]);

    $this->storeReservation($data->id, $reservation->id, $data->hotel, $data->action_description);
  }

  public function cancel($reservationID) {
    $this->repository->cancel($reservationID);
  }

  public function modify($reservation, $data) {
    if ($data->status === "missed") {
      try {
        $reservation->reservation->miss();
      } catch (\Exception $e) {

      }
    }
  }

  private function getAllotment($data, $roomID) { //dirty shit because ostrovok doesn't have a sustainable solution in api
    if (count($data->bedding) === 0) {
      return RoompRoom::find($roomID)->allotments->first()->id;
    }

    if (str_contains(mb_strtolower($data->bedding[0]), 'односп')) {
      return 2;
    } else if (str_contains(mb_strtolower($data->bedding[0]), 'двусп')) {
      return 1;
    } else {
      return RoompRoom::find($roomID)->allotments->first()->id;
    }
  }

  public function getOstrovokData($hotelID) {
    try {
      $hotel = $this->getOstrovokHotel($hotelID);
    } catch (ModelNotFoundException $e) {
      return [
        'hotel_id' => null
      ];
    }

    $rooms = $hotel->rooms;
    $roomp_rooms = $this->hotelRepository->find($hotelID)->rooms()->with('class', 'allotments')->get();

    return [
      'hotel_id' => $hotel->ostrovok_id,
      'rooms' => $rooms,
      'roomp_rooms' => $roomp_rooms
    ];
  }
}