<?php

namespace Roomp;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable {
  use Notifiable;

  public const GUARD = 'admins';

  protected $fillable = [
    'name', 'email', 'password', 'phone'
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
