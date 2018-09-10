<?php

namespace Roomp\Hotels\Rooms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Roomp\Hotels\Traits\HasTranslatableFields;

class RoomClass extends Model {
  use HasTranslatableFields;

  protected $appends = ['name'];
  protected $table = 'room_classes';
  public $timestamps = false;
}
