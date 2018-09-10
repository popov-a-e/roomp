<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Http\Resources\Rooms\AdminShort;

class HotelPolicyResource extends JsonResource {
  public function toArray($request) {
    return array_merge($this->policy->toArray(), [
      'currency' => $this->currency
    ]);
  }
}
