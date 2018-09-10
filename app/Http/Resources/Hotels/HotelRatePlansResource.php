<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Http\Resources\Rooms\AdminShort;

class HotelRatePlansResource extends JsonResource {
  public function toArray($ratePlan) {
    return [
      'rate_id' => (string)$ratePlan->id,
      'name' => $ratePlan->name,
      'rooms' => $this->rooms->pluck('id')->map(function ($el) {
        return (string)$el;
      }),
      'currency' => $this->currency
    ];
  }
}
