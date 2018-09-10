<?php
/**
 * Created by PhpStorm.
 * User: user1
 * Date: 05.10.2017
 * Time: 13:59
 */

namespace Roomp\OTA\Ostrovok;

use Illuminate\Http\Request;

class ConnectionController {
  private $repository;

  public function __construct(Repository $repository) {
    $this->repository = $repository;
  }

  public function get($hotelID) {
    return $this->repository->getOstrovokData($hotelID);
  }

  public function getHotels() {
    return $this->repository->getHotels();
  }

  public function save($hotelID, Request $request) {
    $this->repository->associateHotel($request->input('ostrovok_hotel_id'), $hotelID);
  }

  public function set(Request $request) {
    $map = $request->input('map');

    foreach ($map as $ostrovokID => $roompID) {
      $this->repository->associateRoom($ostrovokID, $roompID);
    }
  }

  public function refresh(Agent $agent) {
    $agent->updateData();
  }
}