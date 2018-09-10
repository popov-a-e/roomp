<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 21.08.2018
 * Time: 18:50
 */

namespace Roomp\Hotels\Traits;


use Roomp\APIUser;
use Roomp\City;
use Roomp\Hotels\Hotel;
use Roomp\Hotels\HotelAmenity;
use Roomp\Hotels\Organizations\Organization;
use Roomp\Hotels\Rooms\Room;
use Roomp\Hotelier;
use Roomp\Hotels\HotelLog;
use Roomp\Hotels\HotelOffer;
use Roomp\Hotels\Policy;
use Roomp\CRS\Reservation;
use Roomp\OTA\Wired\Connection;
use Roomp\Drivers\AmoCRM\Models\Lead;
use Roomp\OTA\Ostrovok\Hotel as OstrovokHotel;

trait HotelRelations {
  public function amenities() {
    return $this->belongsToMany(
      HotelAmenity::class, 'hotels_amenities', 'hotel_id', 'amenity_id'
    );
  }

  public function closest() {
    return $this->belongsToMany(
      Hotel::class, 'hotels_closest', 'hotel_id', 'closest_id'
    );
  }

  public function lead() {
    return $this->hasOne(Lead::class);
  }

  public function bookingConnection() {
    return $this->hasOne(Connection::class);
  }

  public function ostrovokHotel() {
    return $this->hasOne(OstrovokHotel::class);
  }

  public function city() {
    return $this->belongsTo(City::class);
  }

  public function logs() {
    return $this->hasMany(HotelLog::class);
  }

  public function channel() {
    return $this->belongsTo(APIUser::class);
  }

  public function hotelier() {
    return $this->belongsTo(Hotelier::class);
  }

  public function offer() {
    return $this->hasOne(HotelOffer::class);
  }

  public function policy() {
    return $this->hasOne(Policy::class);
  }

  public function reservations() {
    return $this->hasMany(Reservation::class);
  }

  public function rooms() {
    return $this->hasMany(Room::class);
  }

  public function organization() {
    return $this->belongsTo(Organization::class, 'organization_id', 'id');
  }
}