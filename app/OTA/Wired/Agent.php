<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 02.08.2017
 * Time: 13:23
 */

namespace Roomp\OTA\Wired;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Roomp\Drivers\OTA\OTA;
use Roomp\Drivers\OTA\OTAInterface;
use Roomp\Jobs\WiredJob;
use Roomp\Repositories\HotelRepository;

class Agent {
 // private $lcode;
  private $hotelRepository;

  private const BOOKING = 2;

  public function __construct(Driver $driver, Repository $repository) {
    $this->driver = $driver;
    $this->repository = $repository;
  }

  public function setAvailability(int $hotelID, array $avaiability): bool {
    $rooms = [];

    $from = null;

    $hotel = $this->hotelRepository->find($hotelID);
    if ($hotel->disabled) return false;
    if (count($avaiability) === 0) return false;

    $notExistentRooms = [];

    $lcode = $this->repository->getConnection($hotelID)->lcode ?? false;

    if (!$lcode) return false;

    foreach ($avaiability as $roomID => $days) {
      $from = collect($days)->min('date');
      if (array_search($roomID, $notExistentRooms)) continue;

      $room = $this->repository->getRoom($roomID);

      if (!$room) continue;

      $rooms[] = [
        'id' => $room->rid,
        'days' => $this->normalizeArray($days, 'available', 'avail')
      ];
    }

    if (count($rooms) === 0) return false;

    dispatch(new WiredJob((object)[
      'lcode' => $lcode,
      'from' => $from,
      'rooms' => $rooms,
      'type' => 'availability'
    ]));
    //$this->driver->setAvailability($lcode, $from, $rooms);

    return true;
  }

  public function setPrices(int $hotelID, array $pricesBulk): bool {
    $hotel = $this->hotelRepository->find($hotelID);
    if ($hotel->disabled) return false;

    $pricesFlat = collect(collect($pricesBulk)->groupBy('room_id')->reduce(function ($aggregate, $el) {
      $roomID = $el[0]['room_id'];
      $room = $this->repository->getRoom($roomID);

      if (!$room) return $aggregate;

      if (isset($aggregate[$room->lcode])) {
        $aggregate[$room->lcode] = $aggregate[$room->lcode]->merge($el); //array_merge($aggregate[$room->lcode], $el->toArray());
      } else {
        $aggregate[$room->lcode] = $el;
      }

      return $aggregate;
    }, []));

    $pricesFlat->each(function ($prices, $lcode) {
      $prices->groupBy('occupancy')->each(function($pricesByOccupancy) use ($lcode) {
        $pricesByOccupancy->groupBy('rate_id')->each(function($prices) use ($lcode) {
          $rateID = $prices[0]['rate_id'];
          $occupancy = $prices[0]['occupancy'];

          list($prices, $from) = $this->normalizePrices($prices);

          $prices = $prices->first();

          $rateWithMeal = $this->repository->getRate($lcode, $rateID, $occupancy, true);
          $rateWithoutMeal = $this->repository->getRate($lcode, $rateID, $occupancy, false);

          $pidWithMeal = $rateWithMeal->rid ?? false;
          $pidWithoutMeal = $rateWithoutMeal->rid ?? false;

          $pricesWithMeal = [];
          $pricesWithoutMeal = [];

          $prices->each(function($roomPrices, $roomID) use (&$pricesWithMeal, &$pricesWithoutMeal, $occupancy) {
            $room = $this->repository->getRoom($roomID);
            $hotel = $room->room->hotel;

            $breakfastIncluded = $hotel->breakfast;
            $breakfastCost = $hotel->policy->breakfast_price * $occupancy;

            $roomPricesWithMeal = $roomPrices;
            $roomPricesWithoutMeal = $roomPrices;

            if ($breakfastIncluded) {
              $roomPricesWithoutMeal = array_map(function($p) use ($breakfastCost) {return $p - $breakfastCost;}, $roomPrices);
            } else {
              $roomPricesWithMeal = array_map(function($p) use ($breakfastCost) {return $p + $breakfastCost;}, $roomPrices);
            }

            if (count(array_where($roomPricesWithMeal, function($el) {return $el <= 0;})) === 0 && count(array_where($roomPricesWithoutMeal, function($el) {return $el <= 0;})) === 0) {
              $pricesWithMeal[$room->rid] = $roomPricesWithMeal;
              $pricesWithoutMeal[$room->rid] = $roomPricesWithoutMeal;
            }
          });

          if (count($pricesWithMeal) === 0) return;

          if ($pidWithMeal) dispatch(new WiredJob((object)[
            'pid' => $pidWithMeal,
            'lcode' => $lcode,
            'from' => $from,
            'prices' => json_encode($pricesWithMeal),
            'type' => 'prices'
          ]));

          if ($pidWithoutMeal) dispatch(new WiredJob((object)[
            'pid' => $pidWithoutMeal,
            'lcode' => $lcode,
            'from' => $from,
            'prices' => json_encode($pricesWithoutMeal),
            'type' => 'prices'
          ]));
        });
      });
    });

    return true;
  }

  public function setRestrictions(array $restrictions, $force = false) {
    $restrictions = collect(collect($restrictions)->groupBy('room_id')->reduce(function ($aggregate, $el) use ($force) {
      $roomID = $el[0]->room_id;
      $room = $this->repository->getRoom($roomID);

      if (!$room || !$room->lcode || ($room->room->hotel->disabled && !$force)) return $aggregate;

      if (isset($aggregate[$room->lcode])) {
        $aggregate[$room->lcode] = $aggregate[$room->lcode]->merge($el);
      } else {
        $aggregate[$room->lcode] = $el;
      }

      return $aggregate;
    }, []));

    $restrictions->each(function ($restrictions, $lcode) {
      list($normalizedRestrictions, $from) = $this->normalizeRestrictions($restrictions);

      $normalizedRestrictions->each(function ($restrictions, $rateID) use ($from, $lcode) {
        $pid = $this->repository->getRatePlanID($lcode, $rateID);

        if (!$pid) return;

        $restrictions = $restrictions->mapWithKeys(function($roomRestrictions, $roomID) {
          return [$this->repository->getWubookRoomID($roomID) => $roomRestrictions];
        });

//        try {

        dispatch(new WiredJob((object)[
          'pid' => $pid,
          'lcode' => $lcode,
          'from' => $from,
          'restrictions' => json_encode($restrictions),
          'type' => 'restrictions'
        ]));

          //$this->driver->setRestrictions($pid, $lcode, $from, json_decode(json_encode($restrictions)));
        //} catch (\Exception $e) {
          //file_put_contents('/var/www/roomp/1.txt', json_encode($restrictions));
        //}
      });
    });
  }

  public function uploadRooms(int $hotelID) {
    $rooms = $this->repository->transferRooms($hotelID);

    $this->repository->deleteRooms($hotelID);

    $rooms->transform(function ($room) {
      $rid = $this->driver->newRoom($room);

      $this->repository->allocateRoom($room['id'], $room['shortname'], $room['lcode'], $rid * 1);

      return $rid;
    });

    return $rooms;
  }

  public function isConnected(int $hotelID) {
    try {
      $this->repository->getHotelID($hotelID);
    } catch (ModelNotFoundException $exception) {
      return false;
    }

    return true;
  }


  private function normalize($array, $params) {
    $from = null;
    return [collect($array)->groupBy($params['group_by_main'])->map(function ($restrictions) use ($params, &$from) {
      $from = $restrictions->min('from');

      $till = $restrictions->max('till');

      $till = Carbon::parse($till)->addDay();

      return $restrictions->groupBy($params['group_by_secondary'])->map(function ($subset) use ($from, $till, $params) {
        $arr = [];

        for ($i = Carbon::parse($from); $i->diffInDays($till); $i->addDay()) {
          $date = $i->toDateString();
          $el = $subset->where('from', '<=', $date)->where('till', '>=', $date)->first();

          if (!$el) {
            $arr[] = $params['default'];
          } else {
            $arr[] = $params['element']($el);
          }
        }

        return $arr;
      });
    }), $from];
  }

  private function normalizeRestrictions($restrictions) {
    return $this->normalize($restrictions, [
      'group_by_main' => 'rate_id',
      'group_by_secondary' => 'room_id',
      'default' => (object)[],
      'element' => function ($el) {
        $rules = (object)$el->params;

        $params = [];

        foreach ([
                   ['closed' => 'closed'],
                   ['closed_arrival' => 'closed_to_arrival'],
                   ['closed_departure' => 'closed_to_departure'],
                   ['min_stay' => 'minstay'],
                   ['max_stay' => 'maxstay'],
                   ['min_stay_arrival' => 'minstay_on_arrival']
                 ] as $key => $value) {
          foreach ($value as $wubook => $roomp) {
            if (isset($rules->$roomp)) {
              $params[$wubook] = $rules->$roomp;
            }
          }
        }

        return (object)$params;
      }
    ]);
  }

  private function normalizePrices($prices) {
    return $this->normalize($prices, [
      'group_by_main' => 'occupancy',
      'group_by_secondary' => 'room_id',
      'default' => 100000,
      'element' => function ($el) {
        return $el['price'] * 1;
      }
    ]);
  }

  private function normalizeArray(array $array, string $field, $foreign): array {
    $days = [];

    $array = collect($array);

    $from = $array->min('date');
    $till = $array->max('date');

    $tillTime = strtotime($till);

    for ($i = strtotime($from); $i <= $tillTime; $i += 86400) {
      $day = $array->where('date', date('Y-m-d', $i))->first();

      $val = $foreign ? [$foreign => $day[$field]] : $day[$field];

      array_push($days, $day ? $val : []);
    }

    return $days;
  }

  public function missReservation($reservationID, $lcode) {
    $reservation = $this->repository->getRoompReservation($reservationID);

    return $this->driver->missReservation($lcode, $reservation->rcode);
  }

  public function hasReservation($reservationID) {
    return $this->repository->getRoompReservation($reservationID);
  }
}

