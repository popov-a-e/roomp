<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\CRS\Periodic\Price;

class PricesController extends PeriodController {
  public function __construct(Price $prices) {
    parent::__construct($prices);
  }

  public function get(Hotel $hotel, Request $request) {
    return $this->period
      ->from(Carbon::parse($request->from))
      ->till(Carbon::parse($request->till))
      ->atRooms($hotel->rooms()->pluck('id')->toArray())
      ->setRate($request->rate_id)
      ->group();
  }
}
