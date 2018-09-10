<?php

namespace Roomp\Hotels\Rooms;

use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels\Traits\HasTranslatableFields;

class Allotment extends Model {
  use HasTranslatableFields;

  protected $appends = ['name'];
  protected $table = 'allotments';
  public $timestamps = false;
}


