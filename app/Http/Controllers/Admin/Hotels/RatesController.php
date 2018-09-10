<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\HotelRatesResource;

class RatesController extends Controller {
  public function show(Hotel $hotel) {
    return new HotelRatesResource($hotel);
  }

  public function update(Hotel $hotel, Request $request) {
    $hotel->dynamic_roomp_rate = true;
    $hotel->dynamic_roomp_rate_discount = $request->discount;
    $hotel->dynamic_roomp_rate_price_margin = $request->margin;

    $hotel->save();
  }
}
