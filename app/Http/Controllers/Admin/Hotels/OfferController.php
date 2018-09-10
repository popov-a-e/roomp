<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Roomp\Hotels\Hotel;
use Roomp\Http\Controllers\Controller;
use Roomp\Http\Resources\Hotels\Offer\HotelOfferResource;

class OfferController extends Controller {
  public function show(Hotel $hotel) {
    return new HotelOfferResource($hotel);
  }

  public function get(Hotel $hotel) {
    return redirect(Storage::temporaryUrl("offers/{$hotel->offer->filename}", now()->addMinute()));
  }

  public function store(Hotel $hotel) {
    $hotel->offer()->create([]);
    $hotel->hotelier->fill(['email' => null])->save();

    return $hotel->offer;
  }

  public function terminate(Hotel $hotel) {
    return $hotel->offer->terminate();
  }

  public function generate(Hotel $hotel) {
    try {
      $this->validateHotel($hotel);
    } catch (\Exception $e) {
      return response()->json(json_decode($e->getMessage()), 400);
    }

    return $hotel->offer->generate();
  }

  public function regenerate(Hotel $hotel) {
    try {
      $this->validateHotel($hotel);
    } catch (\Exception $e) {
      return response()->json(json_decode($e->getMessage()), 400);
    }

    return $hotel->offer->generate();
  }

  public function sendEmail(Hotel $hotel) {
    $hotel->register();
  }

  public function resetPassword(Hotel $hotel) {
    $hotel->hotelier->resetPassword($hotel->id);
  }

  private function validateHotel($hotel) {
    $validator = Validator::make($hotel->toArray(), [
      'regular_name' => 'required',
      'city_id' => 'required',
      'address_ru' => 'required',
      'reception_email' => 'email|required',
    ]);

    if ($validator->fails()) {
      throw new \Exception(json_encode($validator->errors()->toArray()));
    }
  }
}
