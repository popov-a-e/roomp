<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Roomp\Http\Controllers\Controller;
use Roomp\Repositories\HotelRepository;

class BookingConnectionController extends Controller {
  private $driver;
  private $lcode;
  private $repository;
  private static $BOOKING = 2;

  public function __construct(Driver $driver, Repository $repository) {
    $this->driver = $driver;
    $this->repository = $repository;
    //$this->lcode = 1528099089;
  }

  public function get($hotelID) {
    try {
      return $this->repository->getConnection($hotelID, true);
    } catch (ModelNotFoundException $exception) {
      return 'false';
    }
  }

  public function rooms($hotelID) {
    return $this->repository->getRooms($hotelID);
  }

  public function rates($hotelID) {
    $connection = $this->repository->getConnection($hotelID);

    $pplans = $this->repository->getRates($hotelID);
    $rplans = $this->repository->getRatePlans($hotelID);



    return compact('pplans', 'rplans');
  }

  public function syncRooms($hotelID, Agent $agent) {
    $agent->uploadRooms($hotelID);

    return $this->rooms($hotelID);
  }

  public function createNewOta($hotelID) {
    try {
      $connection = $this->repository->getConnection($hotelID);
      return $connection->chid;
    } catch (\Exception $exception) {

    }

    $account = Account::getAvailable();

    $OTAData = $this->driver->new_ota($account->lcode, self::$BOOKING);

    $this->repository->createConnection($hotelID, $account->lcode, $OTAData['id']);

    return $OTAData['id'];
  }

  public function startProcedure($hotelID, $bookingID) {
    $connection = $this->repository->getConnection($hotelID);

    $response = $this->driver->bcom_start_procedure($connection->lcode, $connection->chid, $bookingID);

    if ($response === 'Ok') {
      $this->repository->assignBookingHotel($hotelID, $bookingID);
    }

    return $response;
  }

  public function confirmActivation($hotelID) {
    $connection = $this->repository->getConnection($hotelID);

    try {
      $response = $this->driver->bcom_confirm_activation($connection->lcode, $connection->chid);
    } catch (\Exception $e) {
      return "false";
    }

    if ($response === 'Ok') {
      $this->repository->setChannelConfirmed($hotelID);
      return "true";
    }

    return $response;
  }

  public function initChannel($hotelID) {
    $connection = $this->repository->getConnection($hotelID);

    $response = $this->driver->bcom_init_channel($connection->lcode, $connection->chid, 'RUB');

    if ($response === 'Ok') {
      return $this->getRoomsRates($hotelID);
    }

    return 'false';
  }

  public function getRoomsRates($hotelID) {
    $connection = $this->repository->getConnection($hotelID);

    $roomsRates = $this->driver->bcom_rooms_rates($connection->lcode, $connection->chid);

    $rooms = $roomsRates['rooms'];
    $rates = $roomsRates['rates'];

    $this->repository->flushMappings($connection->chid);
    $this->repository->createMapping($rooms, $connection->chid);
    $this->repository->createRates($rates, $connection->chid);

    return compact('rooms', 'rates');
  }

  public function setRateMapping(Request $request) {
    $map = $request->input('map');
    $hotelID = $request->input('hotel_id');

    $connection = $this->repository->getConnection($hotelID);

    $mapping = array_map(function($el) {
      return [
        'pplan' => 1 * $el['pplan'],
        'rplan' => 1 * $el['rplan']
      ];
    }, $map);

    $mapping = json_decode(json_encode($mapping));

    $response = $this->driver->bcom_set_rate_mapping($connection->lcode, $connection->chid, $mapping);

    if ($response === 'Ok') {
      $this->repository->setRateMapping($map);
      return 'true';
    }

    throw new \Exception;
  }

  public function setRoomMapping(Request $request) {
    $map = $request->input('map');
    $hotelID = $request->input('hotel_id');

    $connection = $this->repository->getConnection($hotelID);

    $mapping = json_decode(json_encode($map));

    $response = $this->driver->bcom_set_room_mapping($connection->lcode, $connection->chid, $mapping, $mapping);

    if ($response === 'Ok') {
      $this->repository->setMapping($map);
      return 'true';
    }

    return 'false';
  }

  public function closeConnection($hotelID, Repository $repository) {
    $repository->closeConnection($hotelID);
  }

  public function setPremiumProgram($hotelID, Request $request) {
    $this->repository->setPremiumProgram($hotelID, $request->input('is_premium'));
  }
}
