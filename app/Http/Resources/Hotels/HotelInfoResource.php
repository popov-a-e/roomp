<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelInfoResource extends JsonResource {
  public function toArray($request) {
    return array_merge($this->resource->toArray(), [
      'hotelier' => $this->hotelier,
      'channel' => $this->channel,
      'organization' => $this->organization()->with('contacts')->first(),
      'amenities' => $this->amenities->pluck('id')
    ]);
  }
}