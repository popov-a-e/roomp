<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 15.04.2017
 * Time: 21:30
 */

namespace Roomp\OTA\Ostrovok;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class Agent {
  private $legacyDriver;

  public function __construct(Driver $driver, Repository $repository, LegacyDriver $legacyDriver) {
    $this->driver = $driver;
    $this->repository = $repository;
    $this->legacyDriver = $legacyDriver;
  }

  public function updateData() {
    $hotels = $this->driver->getHotels();

    foreach ($hotels as $hotel) {
      $this->repository->checkHotel($hotel);

      $this->repository->updateHotelData($hotel->id);
    }

    return 0;
  }

  public function setPrices(int $hotelID, array $data) : bool {
    $occupancies = collect($data)->map(function($el) {
      try {
        return $this->repository->getOccupanciesArray($el);
      } catch (\Exception $e) {
        return false;
      }
    })->filter(function($el) {return $el !== false;})->values();

    $ostrovokHotelID = $this->repository->getOstrovokHotel($hotelID)->ostrovok_id;

    $occupancies->chunk(50)->each(function($occupancy) use ($ostrovokHotelID) {
      try {
        $this->driver->setPrices($ostrovokHotelID, $occupancy->values()->toArray());
      } catch (\Exception $e) {
        return false;
        //dd ($occupancy->toArray());
      }
    });

    return true;
  }

  public function setAvailability(int $hotelID, array $data) : bool {
    $room_categories = [];
/*
    $available = collect($data)->reduce(function($arr, $row) {
      if (!isset($arr[$row['room_id']])) {
        $arr[$row['room_id']] = [];
      }

      array_push($arr[$row['room_id']], [
        'date' => $row['date'],
        'available' => $row['available']
      ]);
    }, []);*/

    foreach ($data as $roomID => $availability) {
      $room_availability = $this->repository->getRoomCategoriesArray($roomID, $availability);

      $room_categories = array_merge($room_categories, $room_availability);
    }

    $this->driver->setAvailability($hotelID, $room_categories);

    return true;
  }

  public function setRestrictions(int $hotelID, $data) {
    $ratePlans = collect($data)->map(function($restriction) {
      return $this->repository->transformRestrictionArray($restriction);
    })->values()->toArray();

    $this->driver->setRestrictions($hotelID, $ratePlans);
  }

  public function isConnected($hotelID) {
    try {
      $this->repository->getOstrovokHotel($hotelID);
      return true;
    } catch (ModelNotFoundException $e) {
      return false;
    }
  }

  public function hasReservation($reservationID) {
    return !!$this->repository->getRoompReservation($reservationID);
  }

  public function missReservation($reservationID) {
    $reservation = $this->repository->getRoompReservation($reservationID);

    return $this->legacyDriver->missReservation($reservation);
  }
}