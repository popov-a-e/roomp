<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 15.08.2018
 * Time: 18:02
 */

namespace Roomp\Http\Resources\Hotels\Channels\Ostrovok;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionResource extends JsonResource {
  public function toArray($request) {
    return [
      'hotel_id' => $this->ostrovok_id,
      'rooms' => $this->rooms->load('occupancies', 'rates'),
      'roomp_rooms' => $this->hotel->rooms->load('class', 'allotments')
    ];
  }
}