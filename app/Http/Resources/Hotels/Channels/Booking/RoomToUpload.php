<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 15.08.2018
 * Time: 18:02
 */

namespace Roomp\Http\Resources\Hotels\Channels\Booking;

use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Drivers\OTA\Wubook\Room;

class RoomToUpload extends JsonResource {
  const RTYPE_ROOM = 1;
  const RTYPE_APARTMENT = 2;
  const DEFAULT_PRICE = 100000;
  const DEFBOARD = 'nb';

  public function toArray($request = null) {
    return [
      'woodoo' => 0,
      'name' => $this->class->en . ", " . $this->allotments->map(function ($a) {
          return $a->en;
        })->implode(","),
      'shortname' => Room::getNextCode(),
      'defboard' => self::DEFBOARD,
      'lcode' => $this->resource->hotel->bookingConnection->lcode,
      'rtype' => self::RTYPE_ROOM,
      'avail' => 0,
      'beds' => $this->max_guest_number,
      'defprice' => self::DEFAULT_PRICE,
      'id' => $this->id,
      'roomp_room' => $this->resource
    ];
  }

  public function __get($key) {
    if ($key === 'toArray') return $this->toArray();

    return parent::__get($key);
  }
}