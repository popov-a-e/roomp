<?php

namespace Roomp;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
  use Notifiable;

  protected $hidden = [
    'password', 'remember_token',
  ];
}
