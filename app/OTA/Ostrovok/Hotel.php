<?php

namespace Roomp\OTA\Ostrovok;

use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels\Hotel as RoompHotel;

class Hotel extends Model {
  protected $table = 'ostrovok__hotels';
  public $timestamps = false;
  protected $primaryKey = 'ostrovok_id';

  public function rooms() {
    return $this->hasMany(Room::class, 'ostrovok_hotel_id', 'ostrovok_id')->orderBy('ostrovok__rooms.id');
  }

  public function hotel() {
    return $this->belongsTo(RoompHotel::class);
  }


  protected static function boot() {
    parent::boot();

    static::deleting(function($hotel) {
      $hotel->rooms()->each(function($room) {
        $room->delete();
      });
    });
  }
}
