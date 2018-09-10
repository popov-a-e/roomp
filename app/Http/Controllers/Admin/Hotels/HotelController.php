<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Http\Request;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\HotelInfoResource;
use Roomp\Http\Resources\Hotels\StandaloneHotelResource;
use Roomp\Http\Resources\Hotels\HotelVerboseResource as HotelsVerbose;

class HotelController extends Controller {
  private $hotels;

  public function __construct(Hotel $hotel) {
    $this->hotels = $hotel;
  }

  public function index() {
    return HotelsVerbose::collection(
      $this->hotels
        ->admin()
        ->get()
    );
  }

  public function store(Request $request) {
    $hotel = new $this->hotels($request->toArray());

    $hotel->photos_hub = $hotel->photos_hub ?: $hotel->photos;
    $hotel->pretty_url = $hotel->pretty_url ?: str_random();

    $hotel->save();

    $hotel->hotelier
      ->fill($request->input('hotelier') ?? [])
      ->save();

    return $hotel;
  }

  public function show(Hotel $hotel) {
    return new StandaloneHotelResource($hotel);
  }

  public function showInfo(Hotel $hotel) {
    return new HotelInfoResource($hotel);
  }

  public function update(Request $request, Hotel $hotel) {
    $hotel->fill($request->toArray());

    if ($request->amenities) {
      $hotel->amenities()->detach();
      $hotel->amenities()->attach($request->amenities);
    }

    $hotel->hotelier
      ->fill($request->input('hotelier') ?? [])
      ->save();

    return $hotel;
  }

  public function updateComment(Hotel $hotel, Request $request) {
    $hotel->comment = $request->input('comment');

    $hotel->save();
  }

  public function updateGoodyBags(Hotel $hotel, Request $request) {
    $hotel->goody_bags_timestamp = now();
    $hotel->goody_bags_left = $request->input('n');

    $hotel->save();
  }

  public function destroy(Hotel $hotel) {
    $hotel->delete();
  }
}
