<?php

namespace Roomp\Http\Resources\Hoteliers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelierHotelsResource extends JsonResource {
  public function toArray($request) : array {
    return array_merge($this->resource->toArray(), [
      'hotels' => $this->hotels->pluck('regular_name')->implode(', '),
    ]);
  }

  public static function collection($resource) {
    return parent::collection($resource);
  }
}
