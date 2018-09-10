<?php
/**
 * Created by PhpStorm.
 * User: user1
 * Date: 05.10.2017
 * Time: 13:59
 */

namespace Roomp\Http\Controllers\Admin\Hotels\Channels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\Channels\Ostrovok\ConnectionResource;
use Roomp\OTA\Ostrovok\Hotel as OstrovokHotel;
use Roomp\Hotels\Rooms\Room;
use Roomp\OTA\Ostrovok\Room as OstrovokRoom;

class Ostrovok extends Controller {
  private $rooms;
  private $ostrovokRooms;

  public function __construct(Room $room, OstrovokRoom $ostrovokRoom) {
    $this->rooms = $room;
    $this->ostrovokRooms = $ostrovokRoom;
  }

  public function show(Hotel $hotel) {
    return new ConnectionResource($hotel->ostrovokHotel);
  }

  public function associate(Hotel $hotel, OstrovokHotel $ostrovok_hotel) {
    return $ostrovok_hotel->hotel()->associate($hotel);
  }

  public function mapRooms(Request $request) {
    foreach ($request->map as $ostrovokID => $roompID) {
      $ostrovokRoom = $this->ostrovokRooms->find($ostrovokID);
      $room = $this->rooms->find($roompID);

      if ($room->ostrovokRoom) $room->ostrovokRoom->room()->dissociate();
      $ostrovokRoom->room()->associate($room)->save();
    }
  }
}