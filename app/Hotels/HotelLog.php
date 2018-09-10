<?php

namespace Roomp\Hotels;

use Illuminate\Database\Eloquent\Model;
use Roomp\Admin;

class HotelLog extends Model {
  public $appends = ['admin'];
  protected $guarded = [];

  public function getAdminAttribute() {
    return $this->creator->name ?? 'Unknown';
  }

  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  public function creator() {
    return $this->belongsTo(Admin::class, 'admin_id','id');
  }
}
