<?php

namespace Roomp\Hotels;

use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels\Traits\HasTranslatableFields;

class HotelAmenity extends Model {
  use HasTranslatableFields;

  protected $appends = ['name'];

  protected $table = 'hotel_amenities';
  public $timestamps = false;
}
