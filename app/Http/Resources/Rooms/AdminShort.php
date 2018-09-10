<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 08.08.2018
 * Time: 18:27
 */

namespace Roomp\Http\Resources\Rooms;


use Illuminate\Http\Resources\Json\JsonResource;

class AdminShort extends JsonResource {
  public function toArray($request) {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'allotments' => $this->allotments,
      'amenities' => $this->amenities,
      'class' => $this->class,
      'number' => $this->number
    ];
  }
}