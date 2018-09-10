<?php

namespace Roomp;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Roomp\Drivers\DataProviders\Currency;


class PromoCode extends Model {
  protected $casts = [
    'filter' => 'object',
  ];

  public function creator() {
    return $this->belongsTo(Admin::class);
  }

  public function reservations() {
    return $this->hasMany(Reservation::class);
  }

  public function getDiscountTypeAttribute() {
    return explode(' ', $this->discount)[1];
  }

  public function getDiscountValueAttribute() {
    return explode(' ', $this->discount)[0] * 1;
  }

  public function customerDiscount($customerCurrency) {
    return ceil_to_cents($this->discountValue * $this->localCurrencyRatio($customerCurrency));
  }

  public function localCurrencyRatio($localCurrency) {
    $promoCurrency = $this->discountType;
    if ($promoCurrency === '%') return 1;

    $ratio = 1;

    $currencyProvider = app()->make(Currency::class);

    if ($localCurrency !== $promoCurrency) {
      $ratio = 1 * $currencyProvider->getCachedCurrencyRatio($promoCurrency, $localCurrency);
    }

    return $ratio;
  }

  public function absDiscount($price, $currency) {
    $value = null;

    if ($this->discountType !== $currency) {

    }

    switch ($this->discountType) {
      case '%': $value = floor($price * min($this->discountValue, 100) / 100); break;
      default: $value = min($price, $this->discountValue); break;
    }

    return $value;
  }

  public function use() {
    if ($this->is_used === null) return;
    if ($this->is_used === true) throw new PromoCodeException(__('hotel.promo_code_already_used'));

    $this->is_used = true;
    $this->save();
  }

  public function unuse() {
    if ($this->is_used === null) return;

    $this->is_used = false;
    $this->save();
  }

  public function checkApplicability($hotelID, $cityID, $user = null) {
    if ($this->user_id && $user && $user->id !== $this->user_id) {
      throw new PromoCodeException(__('hotel.cannot_use_promo_code'));
    }

    if ($this->from) {
      if (Carbon::parse($this->from)->diffInMinutes(Carbon::now(), false) < 0) throw new PromoCodeException(__('hotel.promo_code_not_started'));
    }

    if ($this->till) {
      if (Carbon::parse($this->till)->diffInMinutes(Carbon::now(), false) > 0) throw new PromoCodeException(__('hotel.promo_code_ended'));
    }

    if ($this->is_used === true) throw new PromoCodeException(__('hotel.promo_code_not_reusable'));

    if ($this->filter) {
      $result = true;

      foreach ($this->filter as $key => $value) {
        switch ($key) {
          case 'hotels': $result = $result && array_search($hotelID, $value->ids) !== false; break;
          case 'cities': $result = $result && array_search($cityID, $value->ids) !== false; break;
        }
      };

      if (!$result) throw new PromoCodeException(__('hotel.promo_code_not_usable'));
    }

    return true;
  }

  public function timesUsed() {
    return $this->reservations()->count();
  }
}
