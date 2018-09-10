<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 08.08.2018
 * Time: 18:27
 */

namespace Roomp\Http\Resources\Rooms;


use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource {
  public function toArray($request) {
    return array_merge($this->resource->toArray(), [
      'amenities' => $this->amenities->pluck('id'),
      'allotments' => $this->allotments->pluck('id'),
      'hotel_photos' => $this->hotel->photos
    ]);
  }
}