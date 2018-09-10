<?php

namespace Roomp\Http\Resources\Organizations;

use Illuminate\Http\Resources\Json\JsonResource;

class Hotels extends JsonResource {
  public function toArray($request): array {
    return array_merge($this->resource->toArray(), [
      'hotels' => $this->hotels->pluck('regular_name')->implode(', '),
    ]);
  }
}
