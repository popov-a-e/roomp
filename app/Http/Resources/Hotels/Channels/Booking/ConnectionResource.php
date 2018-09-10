<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 15.08.2018
 * Time: 18:02
 */

namespace Roomp\Http\Resources\Hotels\Channels\Booking;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionResource extends JsonResource {
  public function toArray($request) {
    return array_merge($this->resource->toArray(), [
      'mappings' => $this->mappings,
      'rates' => $this->rates
    ]);
  }
}