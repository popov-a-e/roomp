<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;
use Roomp\Room as RoompRoom;

class Room extends Model {
  protected $table = 'wubook__rooms';
  public $timestamps = false;
  protected $guarded = ['id', 'room_id'];


  public function room() {
    return $this->belongsTo(RoompRoom::class);
  }

  private static $currentCode;

  public static function getLastCode() {
    return static::$currentCode ?? static::orderBy('id', 'desc')->first()->code ?? 'ZZZZ';
  }

  public static function getNextCode() {
    return static::next(static::getLastCode());
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function ($room) {
      $driver = app()->make(Driver::class);

      $driver->deleteRoom($room->lcode, $room->rid);
    });
  }

  public static function next($code) {
    $arr = [
      $code[0], $code[1], $code[2], $code[3]
    ];

    $normalize = function ($chr) {
      return ord($chr) - 65;
    };

    $count = 0;

    $number = array_reduce($arr, function ($total, $r) use ($normalize, &$count) {
      $count++;
      return $total + $normalize($r) * pow(26, 4 - $count);
    }, 0);

    $number++;

    $code = '';
    for ($i = 0; $i < 4; ++$i) {
      $code = chr(65 + $number % 26) . $code;
      $number /= 26;
    }

    static::$currentCode = $code;

    return $code;
  }
}
