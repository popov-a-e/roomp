<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Roomp\Http\Resources\Hotels\HotelPolicyResource;

class PolicyController extends Controller {
  public function show(Hotel $hotel) {
    return new HotelPolicyResource($hotel);
  }

  public function update(Hotel $hotel, Request $request) {
    $hotel->policy->fill($request->toArray())->save();
  }
}
