<?php

namespace Roomp\Http\Controllers\Admin\Hotels\Channels;

use Illuminate\Http\Request;
use Roomp\OTA\Wired\Agent;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\Channels\Booking\ConnectionResource;
use Roomp\Http\Resources\Rooms\Wubook as WubookRoom;
use Roomp\OTA\Wired\Account as WubookAccount;

class Booking extends Controller {
  public function get(Hotel $hotel) {
    $connection = $hotel->bookingConnection;

    if (!$connection) return response("Connection not found", 404);

    $hotel->bookingConnection->testConnectivity();

    return new ConnectionResource($hotel->bookingConnection);
  }

  public function create(Hotel $hotel) {
    if (!$hotel->bookingConnection) $hotel->bookingConnection()->create(WubookAccount::createConnection());

    return $hotel->bookingConnection()->first()->chid;
  }

  public function start(Hotel $hotel, $bookingID) {
    return $hotel->bookingConnection->assignBookingHotel($bookingID);
  }

  public function confirm(Hotel $hotel) {
    return $hotel->bookingConnection->confirmActivation();
  }

  public function init(Hotel $hotel) {
    return $hotel->bookingConnection->initChannel() ? $this->syncRoomsAndRates($hotel) : 'false';
  }

  public function rooms(Hotel $hotel) {
    return WubookRoom::collection($hotel->rooms()->has('wubook_room')->get());
  }

  public function rates(Hotel $hotel) {
    $connection = $hotel->bookingConnection;

    return [
      'pplans' => $connection->pplans,
      'rplans' => $connection->rplans
    ];
  }

  public function uploadRooms(Hotel $hotel, Agent $agent) {
    $hotel->uploadRoomsToBooking();

    return $this->rooms($hotel);
  }


  public function syncRoomsAndRates(Hotel $hotel) {
    return $hotel->bookingConnection->syncRoomsAndRates();
  }

  public function setRateMapping(Hotel $hotel, Request $request) {
    return $hotel->bookingConnection->setRateMapping($request->input('map')) ? 'true' : 'false';
  }

  public function setRoomMapping(Hotel $hotel, Request $request) {
    return $hotel->bookingConnection->setRoomMapping($request->input('map')) ? 'true' : 'false';
  }

  public function setPremiumProgram(Hotel $hotel, Request $request) {
    $hotel->bookingConnection->fill(['premium_program' => $request->input('is_premium')])->save();
  }
}
