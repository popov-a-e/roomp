<?php

namespace Roomp\Hotels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;
use Roomp\CRS\RatePlan;
use Roomp\Hotels\Notifications\NoShowReminder;
use Roomp\Hotels\Traits\HasTranslatableFields;
use Roomp\Hotels\Traits\HotelRelations;
use Roomp\OTA\Jobs\CloseHotel;
use Roomp\OTA\Jobs\OpenHotel;
use Roomp\Hotels\Notifications\RegistrationStarted;

class Hotel extends Model {
  use SoftDeletes, Notifiable, HasTranslatableFields, HotelRelations;

  protected $dates = ['deleted_at'];
  protected $table = 'hotels';
  protected $appends = ['name'];
  protected $translatable = ['address', 'descrption', 'landmark', 'additional', 'reach'];

  protected $fillable = [
    'city_id',
    'channel_id',
    'hotelier_id',
    'disabled',
    'pretty_url',
    'lat',
    'lng',
    'checkin',
    'checkout',
    'breakfast',
    'payment_by_cash',
    'payment_online',
    'payment_by_card',
    'ru',
    'regular_name',
    'address_ru',
    'reception_phone',
    'reception_email',
    'head_phone',
    'head_email',
    'map',
    'reach_ru',
    'description_ru',
    'landmark_ru',
    'additional_ru',
    'reach_en',
    'description_en',
    'landmark_en',
    'additional_en',
    'reach_ch',
    'description_ch',
    'landmark_ch',
    'additional_ch',
    'photos',
    'en',
    'ch',
    'address_en',
    'address_ch',
    'photos_hub',
    'room_number',
    'comment',
    'status',
  ];

  public function reconcilliationDocument($year, $month) {
    return ReconcilliationDocument::where('hotel_id', $this->id)->where('year', $year)->where('month', $month)->first();
  }

  public function register() {
    if (!$this->reception_email) abort(400, 'No reception_email');

    $this->notify(new RegistrationStarted($this));
  }

  public function uploadRoomsToBooking() {
    if (!$this->bookingConnection) throw new \Exception('No booking connection');

    return $this->bookingConnection->uploadRooms();
  }

  public function getLastLogAttribute() {
    return $this->logs()->orderBy('id', 'desc')->first();
  }

  public function remindOfNoShows() {
    $reservations = $this->reservations->filter->canCancel();

    if ($reservations->count() === 0) return false;

    $this->notify(new NoShowReminder($reservations));
    //dd ($this->reservations->count());
  }

  public function getPriceKoefAttribute() {
    return (100 - $this->dynamic_roomp_rate_discount) / (100 - $this->dynamic_roomp_rate_price_margin) * 1.0;
  }

  public function generateInviteToken(): string {
    $this->registration_token = str_random(128);

    $this->save();

    return $this->registration_token;
  }

  public function scopeAdmin($query) {
    return $query
      ->with('city', 'bookingConnection', 'channel', 'logs', 'hotelier', 'amenities', 'rooms.amenities')
      ->whereHas('lead', function ($query) {
        return $query->where('stage', '!=', 'initial')->orWhere('last_stage_id', 17040187);
      })->orWhereDoesntHave('lead')
      ->orderBy('id', 'desc');
  }

  /*
   * @deprecated
   */
  public function getStatusAttribute() {
    $offer = $this->offer;

    if (!$this->disabled) return 'active';

    if (in_array(($this->lead->status ?? ''), ['initial', 'thinking']) && (!$offer || !$offer->filename)) return 'initial';
    if (!$offer) return 'deleted';
    if (!$offer->filename) return 'deleted';
    if ($offer->terminated_at) return 'deleted';
    if ($offer->accept_date) return 'signed';

    return 'signing';
  }

  public function getMaxGuestNumberAttribute() {
    return $this->rooms->max('max_guest_number');
  }

  /*
   * TODO: refactor this
   */
  public function amenitiesMarked() {
    return HotelAmenity::all()->map(function ($amenity) {
      $amenity->marked = $this->amenities->contains('id', $amenity->id);
      $amenity->name = $amenity->{App::getLocale()};

      return $amenity;
    })->sortByDesc('marked')->values()->map(function ($amenity, $i) {
      $cl = [];
      $max_visible_amenities = 10;

      if ($amenity->marked) array_push($cl, 'marked');
      if ($i >= $max_visible_amenities) {
        array_push($cl, 'hidden');
      }

      $amenity->class_name = implode(' ', $cl);

      return $amenity;
    });
  }

  public function getRatesAttribute() {
    return RatePlan::all();
  }

  public function getReceptionEmailsAttribute() {
    return preg_split('/[, \s]+/', $this->reception_email);
  }

  public function rates() {
    return RatePlan::all()->map(function ($ratePlan) {
      return (object)[
        'rate_id' => (string)$ratePlan->id,
        'name' => $ratePlan->name,
        'rooms' => $this->rooms->pluck('id')->map(function ($el) {
          return (string)$el;
        }),
        'currency' => $this->currency
      ];
    });
  }

  /*
   * TODO: refactor
   */
  public function allPhotos() {
    $roomPhotos = $this->rooms->reduce(function ($arr, $room) {
      return $arr->merge(explode(',', $room->photos));
    }, collect([]));

    return $roomPhotos->merge(explode(',', $this->photos))->filter(function ($p) {
      return $p;
    });
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function ($hotel) {
      /*$hotel->rooms->each(function($room) {
        $room->delete();
      });
      */
      $hotel->reservations()->each(function ($reservation) {
        $reservation->delete();
      });
      /*
      $hotel->offer->delete();

      if ($hotel->lead) $hotel->lead->delete();*/
    });

    static::updated(function () {
      Artisan::call('hotels:closest');
    });

    static::updating(function ($hotel) {
      $isDisabled = $hotel->disabled;
      $wasDisabled = $hotel->getOriginal('disabled');

      if ($isDisabled && !$wasDisabled) {
        dispatch(new CloseHotel($hotel->id));
      }

      if (!$isDisabled && $wasDisabled) {
        dispatch(new OpenHotel($hotel->id));

        $hotel->status = 'active';
      }
    });

    static::created(function ($hotel) {
      $hotel->policy()->create([]);
      $hotel->offer()->create([]);

      if (!$hotel->organization_id) {
        $organization = new Organization();

        $organization->save();

        $hotel->organization()->associate(new Organization())->save();

        $hotel->save();
      }

      if (!$hotel->hotelier_id) {
        $hotelier = new Hotelier();

        $hotelier->save();

        $hotel->hotelier()->associate($hotelier)->save();
      }
    });
  }
}
