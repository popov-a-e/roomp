<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Roomp\Hotels;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\HotelOrganizationResource as HotelOrganization;
use Roomp\Http\Resources\Organizations\Admin as AdminOrganization;
use Roomp\Http\Resources\Organizations\Hotels;
use Roomp\Organization;

class OrganizationController extends Controller {
  private $organizations;

  public function __construct(Organization $organization) {
    $this->organizations = $organization;
  }

  public function index() {
    return Hotels::collection($this->organizations->whereHas('hotels')->get());
  }

  public function show(Organization $organization) {
    return new AdminOrganization($organization);
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
