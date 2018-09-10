<?php

namespace Roomp\CRS\Periodic\Models;

use Illuminate\Support\Facades\Validator;

class Price extends Model {
  protected $table = 'prices';
  public $timestamps = false;

  protected $casts = [
    'price' => 'float'
  ];

  protected $rules = [
    'price' => 'numeric|min:0'
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
    return $this->price * 1;
  }

  public function setVal($value) {
    $this->price = $value;
  }
}
