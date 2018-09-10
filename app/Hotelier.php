<?php

namespace Roomp;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Roomp\Hotels\Hotel;
use Roomp\Mail\Extranet\Registration;

class Hotelier extends Authenticatable {
  use Notifiable;

  protected $table = 'hoteliers';
  protected $fillable = [
    "name",
    "phone",
    "email",
    "organization"
  ];

  public function hotels() {
    return $this->hasMany(Hotel::class, 'hotelier_id', 'id');
  }

  public function getHotelAttribute() {
    if (!$this->isAuthorized()) return false;

    return $this->getCurrentHotel();
  }

  private function isAuthorized() {
    return Auth::guard('hoteliers')->id() === $this->id;
  }

  private function getCurrentHotel() {
    $id = $this->getCurrentHotelID();

    if ($id === false) return false;

    return Hotel::find($this->getCurrentHotelID());
  }

  private function getCurrentHotelID() {
    $user = Auth::guard('hoteliers')->user();

    $hotelsAccepted = $this->hotels()->pluck('id');

    $hotelID = session('hotel');

    $wrongHotel = !$hotelID || !$hotelsAccepted->contains($hotelID);

    if ($wrongHotel) {
      $hotels = $user->hotels;

      if ($hotels->count() === 0) {
        Auth::guard('hoteliers')->logout();
        return false;
      }

      session(['hotel' => $user->hotels->first()->id ?? null]);
    }

    return session('hotel');
  }

  public function selectHotel($id) {
    if (!$this->hotels->where('id', $id)->first()) {
      abort(403, "403 - Unauthorized");
    }

    session(['hotel' => $id]);
  }

  public function selectLocale($locale) {
    $this->locale = $locale;

    return $this->save();
  }

  public function statuses() {
    return $this->morphMany(StatusLog::class, 'user');
  }

  public function registerVisit() {
    $this->last_visit = Carbon::now();

    $this->save();
  }

  public function getRegisteredAttribute() : bool {
    return !!$this->email;
  }

  public function resetPassword($hotelID) {
    $token = str_random(128);

    $hotel = Hotel::where('id', $hotelID)->where('hotelier_id', $this->id)->first();

    if (!$hotel) abort(400);

    $hotel->registration_token = $token;

    $hotel->save();

    $this->email = null;
    if ($this->email) {
      $this->old_email = $this->email;
      $this->old_password = $this->password;
    }

    $this->save();

    $hotel = Hotel::find($hotelID);

    Mail::to($hotel->receptionEmails)
      ->send(new Registration($hotelID, $token));
  }

  protected static function boot() {
    parent::boot();
  }
}
