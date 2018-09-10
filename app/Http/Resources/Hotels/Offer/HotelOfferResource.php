<?php

namespace Roomp\Http\Resources\Hotels\Offer;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelOfferResource extends JsonResource {
  public function toArray($request) {
    return array_merge($this->offer->toArray(), [
      'last_visit' => $this->hotelier->last_visit ?? null,
      'registered' => $this->hotelier->registered ?? false,
      'registration_token' => $this->registration_token
    ]);
  }
}