<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;

class Account extends Model {
  protected $table = 'wubook__accounts';
  public $timestamps = false;

  public function rooms() {
    return $this->hasMany(Room::class, 'lcode', 'lcode');
  }

  public function getIsFullAttribute() {
    return $this->rooms()->count() < 70;
  }

  public static function getAvailable(): Account {
    return self::has('rooms', '<', 70)->orderBy('id', 'asc')->first();
  }

  public static function createConnection() {
    $account = static::getAvailable();

    $driver = app()->make(Driver::class);

    $data = (object)$driver->createBookingConnection($account->lcode);

    return [
      'chid' => $data->id,
      'lcode' => $account->lcode
    ];
  }
}
