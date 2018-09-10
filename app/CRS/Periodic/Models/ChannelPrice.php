<?php

namespace Roomp\CRS\Periodic\Models;

use Illuminate\Support\Facades\Validator;

class ChannelPrice extends Model {
  protected $table = 'channel_prices';
  public $timestamps = false;

  protected $casts = [
    'price' => 'float|min:0'
  ];

  protected $rules = [
    'price' => 'numeric'
  ];

  protected $properties = ['rate_id', 'guest_number'];

  protected static function getDefaults() {
    return [
      'guest_number' => 1,
      'rate_id' => 1,
      'price' => 0
    ];
  }

  public function getValue() {
    return $this->price;
  }

  public function setVal($value) {
    $this->price = $value;
  }
}
