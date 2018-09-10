<?php

namespace Roomp\Hotels\Rooms;

use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels\Traits\HasTranslatableFields;

class RoomAmenity extends Model {
  use HasTranslatableFields;

  protected $appends = ['name'];
  protected $table = 'room_amenities';
  public $timestamps = false;
}
