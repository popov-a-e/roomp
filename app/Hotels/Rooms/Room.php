<?php

namespace Roomp\Hotels\Rooms;

use Illuminate\Database\Eloquent\Model;
use Roomp\OTA\Wired\Room as WubookRoom;
use Illuminate\Support\Facades\DB;
use Roomp\Hotels\Hotel;
use Roomp\RatePlan;
use Roomp\OTA\Ostrovok\Room as OstrovokRoom;


class Room extends Model {
  protected $table = 'rooms';
  protected $fillable = [
    'name',
    'max_guest_number',
    'size',
    'number',
    'room_class_id',
    'photos'
  ];

  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  public function allotments() {
    return $this->belongsToMany(
      Allotment::class, 'rooms_allotments', 'room_id', 'allotment_id'
    );
  }

  public function amenities() {
    return $this->belongsToMany(
      RoomAmenity::class, 'rooms_amenities', 'room_id', 'amenity_id'
    );
  }

  public function ostrovokRoom() {
    return $this->hasOne(OstrovokRoom::class, 'id', 'room_id');
  }

  public function class() {
    return $this->belongsTo(RoomClass::class, 'room_class_id');
  }

  public function APIForm() {
    return [
      'room_id' => (string)$this->id,
      'name' => $this->name,
      'room_occupancies' => array_map(function ($el) {
        return (string)$el;
      }, range(1, $this->max_guest_number)
      )
    ];
  }

  public function wubook_room() {
    return $this->hasOne(WubookRoom::class, 'room_id', 'id');
  }

  public function ratePlans() {
    return RatePlan::all();
  }

  public function hasPhotos() {
    return !!$this->photos;
  }

  public function hasAmenities() {
    return $this->amenities->count() > 0;
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function ($room) {
      DB::table('availability')->where('room_id', $room->id)->delete();
      DB::table('prices')->where('room_id', $room->id)->delete();
      DB::table('restrictions')->where('room_id', $room->id)->delete();
      DB::table('rooms_allotments')->where('room_id', $room->id)->delete();
      DB::table('rooms_amenities')->where('room_id', $room->id)->delete();
    });

    static::updating(function ($room) {
      if ($room->photos !== $room->getOriginal('photos')) {
        $roomPhotos = collect(explode(',', $room->photos));
        $hotelPhotos = collect(explode(',', $room->hotel->photos));
        $hotelPhotosHub = collect(explode(',', $room->hotel->photos_hub));

        $appendMissingPhotos = function ($photosArray) use ($roomPhotos) {
          $arr = $roomPhotos->filter(function ($photo) use ($photosArray) {
            return !$photosArray->contains($photo);
          })->values();

          return $photosArray->concat($arr)->filter(function ($photo) {
            return $photo;
          })->values()->implode(',');
        };

        $room->hotel->photos = $appendMissingPhotos($hotelPhotos);
        $room->hotel->photos_hub = $appendMissingPhotos($hotelPhotosHub);

        $room->hotel->save();
      }
    });
  }
}
