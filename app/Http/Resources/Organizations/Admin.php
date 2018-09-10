<?php

namespace Roomp\Http\Resources\Organizations;

use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource {
  public function toArray($request): array {
    return array_merge([
      'organization' => $this->resource->toArray(),
      'org' => $this->local,
    ]);
  }
}
