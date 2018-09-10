<?php

namespace Roomp\Hotels;

use Illuminate\Database\Eloquent\Model;
use Roomp\Drivers\PDF\OfferGenerator;
use Roomp\Events\OfferAccepted;

class HotelOffer extends Model {
  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  public function accept() {
    $this->accept_date = date('Y-m-d');

    $hotel = $this->hotel;

    $hotel->status = 'active';
    $hotel->save();

    $this->save();

    event(new OfferAccepted($this->id));
  }

  public function generate() {
    $generator = app()->make(OfferGenerator::class);

    $this->filename = $this->filaname ?: $generator->generate($this->hotel);

    $this->save();

    return $this;
  }

  public function terminate() {
    $this->terminated_at = date('Y-m-d');

    $hotel = $this->hotel;

    $hotel->status = 'deleted';
    $hotel->save();

    $this->save();

    return $this;
  }
}
