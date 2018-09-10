<?php

namespace Roomp;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
  use Notifiable;

  protected $fillable = [
    'name', 'email', 'password', 'phone', 'birthday', 'sex_male', 'locale', 'google_client_id'
  ];

  public function promoCodes() {
    return $this->hasMany(PromoCode::class);
  }

  public function reservations() {
    return $this->hasMany(Reservation::class);
  }

  public function reservationsVerbose() {
    return $this->reservations()->with('occupancies.room.class','occupancies.allotment','statusLog.status','hotel')->get()->map(function($reservation) {
      $reservation = $reservation->verboseFormat();

      $reservation->total_str = toCurrency($reservation->total, $reservation->currency);

      return $reservation;
    });
  }

  public function statuses () {
    return $this->morphMany(StatusLog::class, 'user');
  }

  protected $hidden = [
    'password', 'remember_token',
  ];
}
