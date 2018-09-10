<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Http\Resources\Rooms\AdminShort;

class HotelRatesResource extends JsonResource {
  public function toArray($request) {
    return [
      'discount' =>  $this->dynamic_roomp_rate_discount,
      'margin' => $this->dynamic_roomp_rate_price_margin
    ];
  }
}
