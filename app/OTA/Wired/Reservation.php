<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;
use Roomp\Reservation as RoompReservation;

class Reservation extends Model {
  protected $table = 'wubook__reservations';
  public $timestamps = false;

  public function reservation() {
    return $this->belongsTo(RoompReservation::class);
  }
}
