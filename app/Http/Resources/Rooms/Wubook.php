<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 08.08.2018
 * Time: 18:27
 */

namespace Roomp\Http\Resources\Rooms;


use Illuminate\Http\Resources\Json\JsonResource;

class Wubook extends JsonResource {
  public function toArray($request) {
    return array_merge($this->resource->toArray(), [
      'class' => $this->resource->class,
      'allotments' => $this->allotments,
      'wubook_room' => $this->wubook_room
    ]);
  }
}