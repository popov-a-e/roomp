<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Roomp\Hotelier;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hoteliers\HotelierHotelsResource;

class HotelierController extends Controller {
  private $hoteliers;

  public function __construct(Hotelier $hotelier) {
    $this->hoteliers = $hotelier;
  }

  public function index() {
    return HotelierHotelsResource::collection($this->hoteliers->with('hotels')->get());
  }

//  public function store(Request $request) {
//    //
//  }
//
//  public function show($id) {
//    //
//  }
//
//  public function update(Request $request, $id) {
//    //
//  }
//
//  public function destroy($id) {
//    //
//  }
}
