<?php

namespace Roomp\CRS;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Roomp\Drivers\DataProviders\Currency;
use Roomp\Drivers\Tinkoff\FinanceLog;
use Roomp\Events\AvailabilityChanged;
use Roomp\Events\ReservationBooked;
use Roomp\Events\ReservationCancelled;
use Roomp\Events\ReservationCreated;
use Roomp\Events\ReservationMissed;
use Roomp\Events\ReservationPaid;
use Roomp\Events\ReservationRefunded;

use Roomp\Facades\Payture;
use Roomp\Facades\Tinkoff;
use Roomp\Hotels\Hotel;
use function Sodium\crypto_pwhash_scryptsalsa208sha256;

class Reservation extends Model {
  use SoftDeletes;
  protected $table = 'reservations';

  private $suppress_event = false;
  protected $casts = [
    'updated_reservation_at' => 'datetime'
  ];
  protected $appends = ['tillFix', 'discount'];

  public function getFromCarbonAttribute() {
    return Carbon::parse($this->from);
  }

  public function getTillFixAttribute() {
    return $this->tillCarbon->toDateString();
  }

  public function getTillCarbonAttribute() {
    return Carbon::parse($this->till)->addDay();
  }

  public function getPriceAttribute() {
    return $this->total;
  }

  public function canCancel() {
    return true; // TODO
    $checkin_time = Carbon::parse($this->from . " 23:59:59");
    $current_time = Carbon::now();
    $less_than_48_hours = $checkin_time->diffInHours($current_time, false) < 48 && $checkin_time->diffInHours($current_time, false) >= 0;

    return $this->status->status->active && $less_than_48_hours;
  }

  public function scopeOverdue($query) { //TODO
    return $query->whereHas('statusLog', function ($q) {
      /*$q->where('status_log.active', true);

      $q->whereHas('status', function ($q) {
        $q->where('active', true);
        $q->where('online', true);
        $q->where('confirmed', false);
      });*/
    })->where('created_at', '<', Carbon::now()->subMinutes(15));
  }

  public function scopeUnfulfilled($query) { //TODO
    return $query->whereHas('statusLog', function ($q) {

    })->where('from', '<=', date('Y-m-d', strtotime('yesterday')))
      ->where('is_fulfilled', false);
  }

  public function scopeCancelable($query) {
    return $query->where;
  }

  public function scopeUnconfirmed($query) {
    return $query->whereNull('noshow');
  }

  public function getNightsAttribute() {
    return $this->fromCarbon->diffInDays($this->tillCarbon);
  }

  public function getFullPriceAttribute() {
    return $this->occupancies->reduce(function ($sum, $occupancy) {
      return collect($occupancy->prices)->sum() + $sum;
    }, 0);
  }

  public function getIsNRAttribute() {
    return $this->tariff === "NR";
  }

  public function getRoomsNightsAttribute() {
    return $this->occupancies()->count() * $this->nights;
  }

  public function getIsLongStayAttribute() {
    $policy = $this->hotel->booking_policy;
    if (!$policy) return false;

    return $this->nights >= $policy->long_stay_days;
  }

  public function getIsLastMinuteAttribute() {
    $policy = $this->hotel->booking_policy;
    if (!$policy) return false;

    $daysToCheckin = Carbon::parse(Carbon::parse($this->created_at)->toDateString())->diffInDays($this->fromCarbon);

    return $daysToCheckin <= $policy->last_minute_days;
  }

  public function getGoodyBagsAttribute() {
    $periods = ceil($this->nights / 3);
    $guests = $this->occupancies->sum('adults') + $this->occupancies->sum('children');

    return $periods * $guests;
  }

  public function getCalculatedHashAttribute() {
    $hashedData = [
      'from' => $this->from,
      'till' => $this->till,
      'hotel_id' => $this->hotel_id,
      'online' => (bool)$this->status->status->online,
      'guest_name' => $this->guest_name,
      'price' => $this->price,
      'comment' => $this->comment,
      'rooms' => $this->occupancies->map(function ($o) {
        return [
          'room_id' => $o->room_id,
          'prices' => $o->prices,
          'adults' => $o->adults,
          'children' => $o->children + $o->infants
        ];
      })->toArray()
    ];

    return sha1(json_encode($hashedData));
  }

  public function allocateGoodyBags() {
    $hotel = $this->hotel;

    $hotel->goody_bags_left -= $this->goodyBags;
    //$hotel->goody_bags_timestamp = Carbon::now();

    $hotel->save();
  }

  public function deallocateGoodyBags() {
    $hotel = $this->hotel;

    $hotel->goody_bags_left += $this->goodyBags;
    //$hotel->goody_bags_timestamp = Carbon::now();

    $hotel->save();
  }

  public function getDiscountAttribute() {
    return $this->promoCode ? $this->promoCode->absDiscount($this->fullPrice, $this->currency) : 0;
  }

  public function getCustomerDiscountAttribute() {
    return $this->discount * $this->localCurrencyRatio();
  }

  public function localCurrencyRatio() {
    $hotelCurrency = $this->hotel->currency;
    $ratio = 1;

    $currencyProvider = app()->make(Currency::class);

    if ($hotelCurrency !== $this->currency) {
      $ratio = 1 * $currencyProvider->getCachedCurrencyRatio($hotelCurrency, $this->currency);
    }

    return $ratio;
  }

  public function getTotalAttribute() {
    return $this->fullPrice - $this->discount;
  }

  public function getCustomerTotalAttribute() {
    return $this->total * $this->localCurrencyRatio();
  }

  public function getRateAttribute() {
    return $this->occupancies->reduce(function ($sum, $o) {
      return $sum + $o->rate;
    }, 0);
  }

  public function getAvailabilityAttribute() {
    $availability = [];

    $this->occupancies->each(function ($occupancy) use (&$availability) {
      $avail = Availability::where('room_id', $occupancy->room_id)->where('date', '>=', $this->from)->where('date', '<=', $this->till)->get(['date', 'available'])->toArray();

      $availability[$occupancy->room_id] = $avail;
    });

    return $availability;
  }

  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  public function promoCode() {
    return $this->belongsTo(PromoCode::class, 'promo_code_id', 'id');
  }

  public function getStatusAttribute() {
    $status = $this->statusLog()->with('status')->where('active', true)->first();

    if ($status) return $status;

    return $this->statusLog()->orderBy('id', 'desc')->first();
  }

  public function creator() {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function occupancies() {
    return $this->hasMany(Occupancy::class, 'reservation_id', 'id');
  }

  public function statusLog() {
    return $this->hasMany(StatusLog::class);
  }

  public function channel() {
    return $this->belongsTo(Channel::class);
  }

  public function getPaymentAttribute() {
    return $this->payments()->orderBy('id', 'desc')->first();
  }

  public function payments() {
    return $this->hasMany(FinanceLog::class);
  }

  public function getPaymentStatusAttribute() {
    return $this->payment->status ?? null;
  }

  public function updatePaymentStatus() {
    if (!$this->status->status->active) return false;

    $paymentStatus = $this->paymentStatus;
    $status = $this->status->status;

    if (in_array($paymentStatus, ['NEW', 'PREAUTHORIZING', 'FORMSHOWED', '3DS_CHECKING', '3DS_CHECKED', 'CONFIRMING', 'UNKNOWN'])) {
      //Tinkoff::getStatus($this);

      //$paymentStatus = $this->paymentStatus;
    }

    if (($status->online && !$status->confirmed || (!$status->online && $status->active)) && $paymentStatus === 'CONFIRMED') {
      return $this->pay();
    }

    if ($status->online && $status->active && $paymentStatus === 'REFUNDED') {
      return $this->cancel();
    }
  }

  public function makeReservation($data, $user = null) {
    $this->code = $this->generateReservationCode();
    $this->token = $this->generateToken();
    $this->cancel_token = $this->generateToken();

    $this->hotel_id = $data['hotel_id'];
    $this->from = $data['from'];
    $this->till = Carbon::parse($data['till'])->subDay()->toDateString();
    $this->channel_id = Auth::guard('channels')->id() ?: 1;
    $this->tariff = isset($data['tariff']) ? $data['tariff'] : null;
    $this->currency = $data['currency'] ?? $this->hotel->currency;

    $name = $data['guest_name'];

    $this->guest_name = mb_convert_encoding($name, 'UTF-8');
    $this->comment = $data['comment'];

    $this->user_id = $user ? $user->id : Auth::guard()->id();

    $this->save();
  }

  public function fulfill() {
    $this->is_fulfilled = true;

    $this->save();

    $this->allocateGoodyBags();
  }


  public function verbose() {
    $status = $this->status;

    $statusName = $status->status->name;

    if ($status->status->active && $status->status->confirmed && $this->tillCarbon->diffInHours(Carbon::now(), false) >= 0) {
      $statusName = 'fulfilled';
    }

    if (!$status->status->active && $status->guard === 'hoteliers') {
      $statusName = 'missed';
    }


    $can_cancel = $this->canCancel();
    return [
      'reservation' => $this,
      'status' => $this->status,
      'status_name' => __('reservation.' . $statusName),
      'occupancies' => $this->occupancies,//()->with('room')->get(),
      'creator' => $this->creator,
      'currency' => $this->currency,
      'hotel' => $this->hotel,
      'nights' => $this->nights,
      'total' => $this->total,
      'photo' => explode(',', $this->hotel->photos_hub)[0],
      'can_cancel' => $can_cancel
    ];
  }

  public function verboseFormat() {
    $res = (object)(collect($this)->only(['token', 'code', 'from', 'till', 'guest_name', 'admin_comment'])->all());

    $res->till = Carbon::parse($res->till)->addDay()->toDateString();

    $status = $this->status;

    if (!$status) dd("Status Log damaged for reservation_id={$this->id}");

    $statusName = $status->status->name;

    if ($status->status->active && $status->status->confirmed && $this->tillCarbon->diffInHours(Carbon::now(), false) >= 0) {
      $statusName = 'fulfilled';
    }

    if (!$status->status->active && $status->guard === 'hoteliers') {
      $statusName = 'missed';
    }

    $res->id = $this->id;
    $res->hotel = $this->hotel->name;
    $res->nights = $this->nights;
    $res->total = $this->total;

    $res->occupancies = $this->occupancies->map(function ($occupancy) {
      return $occupancy->room->class->{App::getLocale()} . ', ' . $occupancy->allotment->{App::getLocale()};
    });

    $res->status_name = $status ? __("reservation.{$statusName}") : 'undefined';
    $res->status_time = $status ? $status->timestamp : '';
    $res->status_key = $statusName;
    $res->goody_bags = $this->goodyBags;

    $res->online = $status->status->online ? 'Онлайн' : 'В отеле';

    $res->city = $this->hotel->city->{App::getLocale()};
    $res->channel = $this->channel->name;
    $res->currency = $this->currency;
    $res->hotel_currency = $this->hotel->currency;

    return $res;
  }

  public function allocateBooked() {
    $this->occupancies->each(function ($occupancy) {
      $roomID = $occupancy->room_id;
      $from = $this->from;
      $till = $this->till;

      DB::table('booked')->where('date', '>=', $from)->where('date', '<=', $till)->where('room_id', $roomID)->update([
        'booked' => DB::raw('booked + 1')
      ]);
    });
  }

  public function deallocateBooked() {
    $this->occupancies->each(function ($occupancy) {
      $roomID = $occupancy->room_id;
      $from = $this->from;
      $till = $this->till;

      DB::table('booked')->where('date', '>=', $from)->where('date', '<=', $till)->where('room_id', $roomID)->update([
        'booked' => DB::raw('booked - 1')
      ]);
    });
  }

  private function allocate() {
    if ($this->status && $this->status->status->active) abort(400, "Reservation is already active");
    $this->occupancies->each(function ($occupancy) {
      $roomID = $occupancy->room_id;
      $from = $this->from;
      $till = $this->till;

      Availability::where('date', '>=', $from)->where('date', '<=', $till)->where('room_id', $roomID)->update([
        'available' => DB::raw('available - 1')
      ]);

      DB::table('wubook__availability')->where('date', '>=', $from)->where('date', '<=', $till)->where('room_id', $roomID)->update([
        'available' => DB::raw('available - 1')
      ]);
    });

    $this->allocateBooked();
    event(new AvailabilityChanged($this->hotel_id, $this->availability));
  }

  private function deallocate() {
    if (!$this->status->status->active) abort(400, "Reservation is already inactive");
    $this->deallocateBooked();
    if ($this->overbooking) return false;
    $this->occupancies->each(function ($occupancy) {
      $roomID = $occupancy->room_id;
      $from = $this->from;
      $till = $this->till;

      DB::table('wubook__availability')->where('date', '>=', $from)->where('date', '<=', $till)->where('room_id', $roomID)->update([
        'available' => DB::raw('available + 1')
      ]);

      $max = $occupancy->room->number;

      DB::table('availability')
        ->leftJoin('booked', function ($join) {
          $join->on('booked.room_id', '=', 'availability.room_id');
          $join->on('booked.date', '=', 'availability.date');
        })
        ->leftJoin('wubook__availability', function ($join) {
          $join->on('wubook__availability.room_id', '=', 'availability.room_id');
          $join->on('wubook__availability.date', '=', 'availability.date');
        })
        ->where('availability.room_id', $roomID)
        ->where('availability.date', '>=', $from)
        ->where('availability.date', '<=', $till)
        ->update([
          'available' => DB::raw("wubook__availability.available")//DB::raw("LEAST(wubook__availability.available,$max - booked.booked)")
        ]);
    });

    if ($this->is_fulfilled) $this->deallocateGoodyBags();
    event(new AvailabilityChanged($this->hotel_id, $this->availability));
  }

  public function calculateRate() {
    $this->occupancies->each(function ($o) {
      try {
        $o->calculateRates();
      } catch (\Exception $e) {

      }
    });
  }

  public function calculatePrice() {
    $from = $this->from;
    $till = $this->tillFix;

    $policy = $this->hotel->policy;

    $this->occupancies->each(function ($o) use ($from, $till, $policy) {
      try {
        $o->actualizePrice($from, $till, $policy);
      } catch (\Exception $e) {

      }
    });
  }

  public function reserve(bool $online) {
    $this->allocate();
    $this->mutateState(true, $online, false);
    $this->usePromoCode();
    event(new ReservationCreated($this));
  }

  private function usePromoCode() {
    if ($this->promoCode) {
      $this->promoCode->use();
    }
  }

  private function unusePromoCode() {
    if ($this->promoCode) {
      $this->promoCode->unuse();
    }
  }

  public function book() {
    $this->mutateState(true, false, true);
    event(new ReservationBooked($this));
  }

  public function pay() {
    $this->mutateState(true, true, true);
    event(new ReservationPaid($this));
  }

  public function cancel() {
    $this->unusePromoCode();
    if ($this->status->status->online) {
      if ($this->status->status->confirmed) {
        if (!$this->payment || $this->payment->status === 'REFUNDED') {
          $this->cancelOnline();
        } else {
          $this->refund();
        }
      } else {
        $this->unconfirm();
      }
    } else {
      $this->cancelOffine();
    }
  }

  private $overbooking = false;

  public function overbooking() {
    $this->overbooking = true;

    $this->cancel();
  }

  public function miss() {
    if (!$this->canCancel()) {
      abort(403, '');
    }

    $byHotelier = !Auth::guard('admins')->check() && Auth::guard('hoteliers')->check();

    if (!$byHotelier) {
      Auth::guard('hoteliers')->login($this->hotel->hotelier);
    }

    $this->suppress_event = true;

    $this->cancel();

    $this->noshow = true;
    $this->save();

    event(new ReservationMissed($this));

    if (!$byHotelier) {
      Auth::guard('hoteliers')->logout();
    }
  }

  public function cancelOffine() {
    $this->deallocate();
    $this->mutateState(false, false, true);
    if (!$this->suppress_event) event(new ReservationCancelled($this));
  }

  public function refund() {
    if ($this->channel->id !== 1) {
      $this->cancelOnline();
      return true;
    }

    if (Tinkoff::refund($this)) {
      $this->cancelOnline();
      return true;
    }

    return false;
  }

  public function cancelOnline() {
    $this->deallocate();
    $this->mutateState(false, true, true);
    if (!$this->suppress_event) event(new ReservationRefunded($this));
  }

  public function unconfirm() {
    $this->deallocate();
    $this->mutateState(false, true, false);
    //event(new ReservationCancelled($this));
  }

  public function mutateState(bool $active, bool $online, bool $confirmed) {
    $statusID = Status::byParams($active, $online, $confirmed);

    StatusLog::where('reservation_id', $this->id)->update(['active' => false]);

    $record = new StatusLog;

    $record->reservation_id = $this->id;

    $guards = collect(['hoteliers', 'channels', 'admins', 'users']);

    $guards->each(function ($guard) use (&$record) {
      if ($record->user_id) return;

      $id = Auth::guard($guard)->id();

      if (!$id) return;

      $record->user_id = $id;
      $record->guard = $guard;
    });

    $record->status_id = $statusID;
    $record->timestamp = Carbon::now();

    $record->save();

    $this->updated_at = Carbon::now();
    $this->channel_confirmed = false;

    $this->save();
  }

  private function generateReservationCode() {
    $code = '';

    for ($i = 0; $i < $letters = 4; ++$i) {
      $code .= chr(rand(65, 90));
    }

    for ($i = 0; $i < $numbers = 4; ++$i) {
      $code .= rand(0, 9);
    }

    return $code;
  }

  private function generateToken() {
    return str_random(128);
  }

  public function updateHash() {
    $this->hash = $this->calculatedHash;
    $this->updated_reservation_at = Carbon::now();
    $this->channel_confirmed = false;

    $this->save();
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function ($reservation) {
      if ($reservation->status && $reservation->status->status->active) {
        $reservation->deallocate();
      }

      $reservation->occupancies()->delete();
      $reservation->statusLog()->delete();
    });

    static::updated(function (self $reservation) {
      if ($reservation->calculatedHash !== $reservation->hash) {
        $reservation->updateHash();
      }
    });
  }
}
