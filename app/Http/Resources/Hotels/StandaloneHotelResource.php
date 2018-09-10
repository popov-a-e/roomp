<?php

namespace Roomp\Http\Resources\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Roomp\Http\Resources\Rooms\AdminShort;

class StandaloneHotelResource extends JsonResource {
  private function getGoodyBagsNeededNextMonth() {
    $tomorrow = Carbon::tomorrow();
    $nextMonth = Carbon::tomorrow()->addMonth();

    return $this->reservations()->with('statusLog.status')->where('from', '>=', $tomorrow)->where('from', '<=', $nextMonth)->get()->reduce(function ($sum, $r) {
      if (!$r->status->status->active) return $sum;
      return $sum + $r->goodyBags;
    }, 0);
  }

  public function toArray($request) {
    return array_merge($this->resource->toArray(), [
      'goody_bags_needed_next_month' => $this->getGoodyBagsNeededNextMonth(),
      'contact_face' => $this->organization->contacts->first() ?? null,
      'hotelier' => $this->hotelier,
      'rooms' => AdminShort::collection($this->rooms()->with('allotments', 'class', 'amenities')->get()),
      'amenities' => $this->amenities
    ]);
  }
}
