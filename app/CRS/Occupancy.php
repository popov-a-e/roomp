<?php

namespace Roomp\CRS;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels\Rooms\Allotment;
use Roomp\Drivers\DataProviders\Currency;
use Roomp\Hotels\Rooms\Room;

class Occupancy extends Model {
  protected $table = 'occupancy';
  public $timestamps = false;
  const tariffs = [
    'Standard' => 1,
    'Non-Refundable' => 2,
    'Long Stay' => 3,
    'Last Minute' => 4
  ];

  protected $casts = [
    'rates' => 'array',
    'prices' => 'array'
  ];

  protected $appends = [
    'price'
  ];

  public function room() {
    return $this->belongsTo(Room::class);
  }

  /*
    public function hotel() {
      return $this->belongsTo(Hotel::class);
    }*/

  public function allotment() {
    return $this->belongsTo(Allotment::class);
  }

  public function reservation() {
    return $this->belongsTo(Reservation::class);
  }

  public function getLegacyPriceAttribute() {
    return $this->attributes['price'];
  }

  public function getPriceAttribute() {
    return collect($this->prices)->avg();
  }

  public function getCustomerPriceAttribute() {
    $hotelCurrency = $this->reservation->hotel->currency;
    $ratio = 1;

    $currencyProvider = app()->make(Currency::class);

    if ($hotelCurrency !== $this->reservation->currency) {
      $ratio = 1 * $currencyProvider->getCachedCurrencyRatio($hotelCurrency, $this->reservation->currency);
    }

    return $this->price * $ratio;
  }

  public function actualizePrice($from = null, $till = null, $policy = null) {
    $from = $from ?? $this->reservation->from;
    $till = $till ?? $this->reservation->tillFix;

    $policy = $policy ?? $this->reservation->hotel->policy;

    $currencyProvider = app()->make(Currency::class);
    $guest_number = min($this->room->max_guest_number, $this->adults);

    $base_prices = DB::table('prices')->where('date', '>=', $from)->where('date', '<', $till)->where('room_id', $this->room_id)->where('guest_number', $guest_number)->where('rate_id', 1)->get(['date', 'price']);

    if (!$base_prices) throw new \Exception("Missing price for given guest number");

    $additional_adults_price = ($this->adults - $guest_number) * $policy->price_adults;
    $children_price = $this->children * $policy->price_children;
    $infants_price = $this->infants * $policy->price_infants;

    /*
        $ratio = 1;
        if ($this->room->hotel->currency !== $this->reservation->currency) {
          $ratio = $currencyProvider->getCachedCurrencyRatio($this->room->hotel->currency, $this->reservation->currency);
        }

        $additional_adults_price = ($this->adults - $guest_number) * ceil_to_cents($ratio * $policy->price_adults);
        $children_price = $this->children * ceil_to_cents($ratio * $policy->price_children);
        $infants_price = $this->infants * ceil_to_cents($ratio * $policy->price_infants);
    */
//    dd ($additional_adults_price, $children_price, $infants_price);

    $prices = [];
    for ($i = strtotime($from); $i < strtotime($till); $i += 86400) {
      $p = $base_prices->where('date', date('Y-m-d', $i))->first();

      $price = $p->price;
      /*
            if ($this->room->hotel->currency !== $this->reservation->currency) {
              $price = ceil_to_cents(1.0 * $price * $currencyProvider->getCachedCurrencyRatio($this->room->hotel->currency, $this->reservation->currency));
            }*/

      $price = $price + $additional_adults_price + $children_price + $infants_price;

      //$price /= ceil_to_cents($price / $ratio);


      array_push($prices, $price);
    }

    $this->prices = $prices;

    $this->save();
  }

  public function getOccupancyAttribute() {
    return min($this->room->max_guest_number, $this->adults);
  }

  public function getRateAttribute($policy = null) {
    $policy = $policy ?: $this->reservation->hotel->policy;
    $guest_number = $this->occupancy;

    $additional_adults_price = ($this->adults - $guest_number) * $policy->price_adults;
    $children_price = $this->children * $policy->price_children;
    $infants_price = $this->infants * $policy->price_infants;

    $general_rate = collect($this->rates)->sum();

    return $general_rate;// + $additional_adults_price + $children_price + $infants_price;
  }

  public function calculateRates() {
    $hotel = $this->reservation->hotel;

    if ($hotel->dynamic_roomp_rate) {
      $this->rates = $this->getDynamicRates();
    } else {
      $this->rates = $this->getStaticRates();
    }

    return $this->save();
  }

  private function getStaticRates() {
    $reservation = $this->reservation;

    $seasons = $reservation->hotel->seasons->sortByDesc('default');
    $policy = $reservation->hotel->policy;

    if ($seasons->count() === 0) throw new \Exception('No roomp rates');

    $rates = [];

    $rateTotal = 0;

    $extra_beds = $policy->bed_number;
    $adults = min($this->room->max_guest_number, $this->adults);
    $extra_adults = $this->adults - $adults;


    for ($i = Carbon::parse($reservation->from); $i->diffInDays($reservation->tillCarbon) > 0; $i->addDay()) {
      $season = $seasons->reduce(function ($cur, $season) use ($i) {
        if ($season->checkAgainstDate($i)) return $season;
        return $cur;
      });

      $rate = $season->rates->where('room_class_id', $this->room->room_class_id)->where('guest_number', $adults)->first()->rate * 1;
      $r = $this->applySpecialDiscount($season, $reservation, $rate);

      array_push($rates, $r);
    }

    return $rates;
  }

  private function getChannelPrices($rate) {
    return ChannelPrice::where('room_id', $this->room_id)
      ->where('rate_id', $rate)
      ->where('guest_number', $this->occupancy)
      ->where('date', '>=', $this->reservation->from)
      ->where('date', '<=', $this->reservation->till)
      ->orderBy('date')
      ->get();
  }

  private function getDynamicRates() {
    $currencyProvider = app()->make(Currency::class);
    $reservation = $this->reservation;
    $hotel = $reservation->hotel;
    $policy = $hotel->policy;

    $rate = 1;
    if ($this->is_nr) $rate = 2;
    else if ($reservation->isLongStay) $rate = 3;
    else if ($reservation->isLastMinute) $rate = 4;

    $prices = $this->getChannelPrices($rate);

    $discount = $this->reservation->hotel->dynamic_roomp_rate_discount;

    if ($prices->count() < $reservation->nights) {
      $prices = $this->getChannelPrices(1);
    }

    $mealPrice = ($this->breakfast_included - $hotel->breakfast) * $policy->breakfast_price * $this->adults;

    $lastMinute = Carbon::parse($reservation->from)->addDay()->addHours($reservation->hotel->city->utc_offset);
    $hoursToLastMinute = $reservation->created_at->diffInHours($lastMinute, false);

    $getLastMinuteDiscount = function() use ($policy, $hoursToLastMinute) {
      foreach ($policy->lastMinutes as $hours => $discount) {
        if (intval($hours) > $hoursToLastMinute) return intval($discount);
      }

      return 0;
    };

    $lastMinuteDiscountMultiplier = (100 - $getLastMinuteDiscount()) / 100;

    return $prices->map(function ($price) use ($discount, $currencyProvider, $mealPrice, $lastMinuteDiscountMultiplier) {
    /*    if ($this->reservation->currency !== $price->currency) {
          $price->price = 1.0 * $price->price * $currencyProvider->getCachedCurrencyRatio($price->currency, $this->reservation->currency);
        }
    */

      $price = $price->price * $lastMinuteDiscountMultiplier;

      return ceil_to_cents($price * (100 - $discount) / 100) + $mealPrice;
    })->values()->toArray();
  }

  private function applySpecialDiscount($season, $reservation, $rate) {
    $discountRate = function ($discount, $rate) {
      $disc = explode(' ', $discount);

      if ($disc[1] === '%') return floor($rate * (100 - $disc[0] * 1) / 100);

      return $rate - $disc[0] * 1;
    };

    $discountedNR = $reservation->isNR ? $discountRate($season->nr_discount, $rate) : $rate;
    $discountedLS = $reservation->isLongStay ? $discountRate($season->long_stay_discount, $rate) : $rate;
    $discountedLM = $reservation->isLastMinute ? $discountRate($season->last_minute_discount, $rate) : $rate;

    return min($discountedLM, $discountedLS, $discountedNR);
  }

  public function getAvailabilityAttribute() {
    return Availability::where('room_id', $this->room_id)->where('date', '>=', $this->reservation->from)->where('date', '<=', $this->reservation->till)->get()->toArray();
  }
}
