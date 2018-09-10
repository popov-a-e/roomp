<?php

namespace Roomp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class BusinessDeveloper extends Authenticatable {
  use Notifiable;

  protected $fillable = [
    'name', 'email', 'password', 'phone', 'amo_id'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  public function statuses () {
    return $this->morphMany(StatusLog::class, 'user');
  }
}
