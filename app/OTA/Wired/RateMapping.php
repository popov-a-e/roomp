<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;

class RateMapping extends Model {
  public $timestamps = false;
  protected $table = 'wubook__rate_mappings';
  protected $guarded = ['id'];
}
