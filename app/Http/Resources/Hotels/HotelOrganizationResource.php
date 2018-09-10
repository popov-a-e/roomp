<?php

namespace Roomp\Http\Resources\Hotels;

use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Http\Resources\Organizations\Admin as AdminOrganization;

class HotelOrganizationResource extends JsonResource {
  public function toArray($request): array {
    return array_merge($this->resource->toArray(), (new AdminOrganization($this->organization))->toArray($request), [
      'hotelier' => $this->hotelier
    ]);
  }
}
