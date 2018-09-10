<?php

namespace Roomp\OTA\Ostrovok;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
  protected $table = 'ostrovok__reservations';
  public $timestamps = false;

  public function reservation() {
    return $this->belongsTo(\Roomp\Reservation::class, 'reservation_id', 'id');
  }
}
