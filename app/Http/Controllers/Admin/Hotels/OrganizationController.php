<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\HotelOrganizationResource as HotelOrganization;
use Roomp\Http\Resources\Organizations\Admin as AdminOrganization;
use Roomp\Http\Resources\Organizations\Hotels;
use Roomp\Hotels\Organizations\Organization;

class OrganizationController extends Controller {
  public function show(Hotel $hotel) {
    return new HotelOrganization($hotel);
  }

  public function update(Request $request, Hotel $hotel, Organization $organization) {
    return $organization->fillLocal($hotel, $request->toArray());
  }

  public function updateLocales(Request $request, Hotel $hotel, Organization $organization) {
    $hotel->currency = $request->input('currency') ?? $hotel->currency;
    $organization->locale = $request->input('country') ?? $organization->locale;
    $hotel->hotelier->locale = $request->input('language') ?? $hotel->hotelier->locale;

    $hotel->save();
    $hotel->hotelier->save();
    $organization->save();
  }

//
//    public function store(Request $request)
//    {
//        //
//    }
//
//
//    public function destroy($id)
//    {
//        //
//    }
}
