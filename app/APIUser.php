<?php

namespace Roomp;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class APIUser extends Authenticatable {
  use Notifiable;

  protected $table = 'api_users';

  protected $guarded = [];
}

