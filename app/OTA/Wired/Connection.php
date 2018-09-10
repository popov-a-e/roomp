<?php

namespace Roomp\OTA\Wired;

use Illuminate\Database\Eloquent\Model;
use Roomp\Events\WubookConnectionBroken;
use Roomp\Hotels\Hotel;
use Roomp\CRS\RatePlan;
use Roomp\Http\Resources\Hotels\Channels\Booking\RoomToUpload as WubookRoomToUpload;

class Connection extends Model {
  protected $table = 'wubook__connections';
  public $timestamps = false;
  protected $guarded = ['id'];
  protected $casts = [
    'lcode' => 'integer',
    'chid' => 'integer'
  ];

  private $driver;

  private function driver() {
    if (!$this->driver) $this->driver = app()->make(Driver::class);

    return $this->driver;
  }

  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  public function mappings() {
    return $this->hasMany(ConnectionMapping::class, 'chid', 'chid');
  }

  public function rates() {
    return $this->hasMany(RateMapping::class, 'chid', 'chid');
  }

  private function getOTAs() {
    return collect($this->driver()->getOTAs($this->lcode));
  }

  public function getOTAAttribute() {
    return $this->getOTAs()->where('id', $this->chid)->first();
  }

  public function testConnectivity() {
    $active = $this->OTA['running'] ?? false;

    if ($this->last_active && !$active) {
      event(new WubookConnectionBroken($this->hotel_id));
    }

    $this->last_active = $active ? now() : null;

    $this->save();
  }

  public function getPplansAttribute() {
    return Rate::where('lcode', $this->lcode)->get()->filter(function ($el) {
      return $el->occupancy <= $this->hotel->maxGuestNumber;
    });
  }

  public function getRplansAttribute() { // TODO
    $arr = Restriction::where('lcode', $this->lcode)->get();

    return RatePlan::all()->map(function ($rplan) use ($arr) {
      $rplan->wubook_id = $arr->where('rate_id', $rplan->id)->first()->rplan_id ?? null;

      return $rplan;
    })->where('wubook_id', '<>', null);
  }

  public function assignBookingHotel(int $bookingHotelID) {
    $response = $this->driver()->bcom_start_procedure($this->lcode, $this->chid, $bookingHotelID);

    if ($response === 'Ok') {
      $this->booking_hotel_id = $bookingHotelID;

      $this->save();
    }

    return $response;
  }

  public function confirmActivation() {
    try {
      $response = $this->driver()->bcom_confirm_activation($this->lcode, $this->chid);
    } catch (\Exception $e) {
      return "false";
    }

    if ($response === 'Ok') {
      $this->channel_confirmed = true;

      $this->save();

      return "true";
    }

    return $response;
  }

  public function initChannel() {
    $response = $this->driver()->bcom_init_channel($this->lcode, $this->chid, $this->hotel->currency); // TODO : currency ?

    return $response === 'Ok';
  }

  private function wubookRoomAdapter($booking_room_id, $roomData) {
    return [
      'booking_room_id' => $booking_room_id,
      'booking_name' => $roomData['name']
    ];
  }

  private function wubookRateAdapter($booking_rate_id, $rateData) {
    return [
      'booking_rate_id' => $booking_rate_id,
      'booking_name' => $rateData['name']
    ];
  }

  public function syncRoomsAndRates() {
    $roomsRates = $this->driver()->bcom_rooms_rates($this->lcode, $this->chid);

    $this->clear();

    $this->mappings()->createMany(collect($roomsRates['rooms'])->map(function($roomData, $booking_room_id) {
      return $this->wubookRoomAdapter($booking_room_id, $roomData);
    })->toArray());
    $this->rates()->createMany(collect($roomsRates['rates'])->map(function($rateData, $booking_rate_id) {
      return $this->wubookRateAdapter($booking_rate_id, $rateData);
    })->toArray());

    return $roomsRates;
  }

  public function setRoomMapping(array $map) {
    $mapping = json_decode(json_encode($map));

    if ($this->driver()->bcom_set_room_mapping($this->lcode, $this->chid, $mapping, $mapping) !== 'Ok') return false;

    foreach ($map as $booking_room_id => $wubook_id) {
      $this->mappings()->where('booking_room_id', $booking_room_id)->update(['wubook_room_id' => $wubook_id]);
    }

    return true;
  }

  public function setRateMapping(array $map) {
    $mapping = json_decode(json_encode($map));

    if ($this->driver()->bcom_set_rate_mapping($this->lcode, $this->chid, $mapping) !== 'Ok') return false;

    foreach ($mapping as $booking_rate_id => $data) {
      $this->rates()->where('booking_rate_id', $booking_rate_id)->update([
        'wubook_rate_id' => $data->pplan,
        'wubook_rate_plan_id' => $data->rplan
      ]);
    }

    return true;
  }

  public function uploadRooms() {
    $this->hotel->rooms->map->wubook_room->rejectEmpty()->each->delete();

    $this->hotel->rooms->map(function($room) {
      [$rid, $code] = $this->driver()->newRoom((new WubookRoomToUpload($room))->toArray());
      $lcode = $this->lcode;

      $room->wubook_room()->create(compact('rid', 'code', 'lcode'));
    });
  }

  public function clear() {
    $this->mappings->each(function ($mapping) {
      $mapping->delete();
    });

    $this->rates->each(function ($rate) {
      $rate->delete();
    });
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function ($connection) {
      $connection->clear();
    });
  }
}
