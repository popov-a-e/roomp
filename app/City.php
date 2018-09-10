<?php

namespace Roomp;

use Illuminate\Database\Eloquent\Model;
use Roomp\Events\CityCreatedEvent;
use Roomp\Hotels\Traits\HasTranslatableFields;

class City extends Model {
  use HasTranslatableFields;

  protected $appends = ['name'];

  protected $table = 'cities';
  public $timestamps = false;

  protected static function boot() {
    parent::boot();

    static::created(function() {
      event(new CityCreatedEvent(json_decode(self::all()->toJson())));
    });
  }
}
