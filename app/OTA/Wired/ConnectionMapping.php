<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;

class ConnectionMapping extends Model {
  protected $table = 'wubook__connection_mappings';
  public $timestamps = false;
  protected $guarded = ['id'];

  public function room() {
    return $this->belongsTo(Room::class, 'wubook_room_id', 'rid');
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function($mapping) {
      if ($mapping->wubook_room_id) $mapping->room->delete();
    });
  }
}
