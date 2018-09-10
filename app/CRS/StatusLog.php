<?php

namespace Roomp\CRS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StatusLog extends Model {
  protected $table = 'status_log';
  public $timestamps = false;

  public function status() {
    return $this->belongsTo(Status::class);
  }

  public function user() {
    return $this->morphTo('user','guard','user_id');
  }

  protected $guarded = [];
}
