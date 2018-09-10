<?php

namespace Roomp\OTA\Ostrovok;

use Illuminate\Database\Eloquent\Model;
use Roomp\OTA\Ostrovok\Room as RoompRoom;

class Room extends Model {
  protected $table = 'ostrovok__rooms';
  public $timestamps = false;
  protected $primaryKey = 'ostrovok_id';

  public function rates() {
    return $this->hasMany(Rate::class, 'ostrovok_room_id', 'ostrovok_id')->orderBy('id');
  }

  public function occupancies() {
    return $this->hasMany(Occupancy::class, 'ostrovok_room_id', 'ostrovok_id')->orderBy('id');
  }

  public function room() {
    return $this->belongsTo(RoompRoom::class, 'room_id', 'id');
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function($room) {
      $room->rates()->delete();
      $room->occupancies()->delete();
    });
  }
}
