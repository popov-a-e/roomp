<?php

namespace Roomp\CRS\Periodic\Models;

class Availability extends Model {
  public $timestamps = false;
  protected $table = 'availability';

  protected $rules = [
    'available' => 'integer|min:0'
  ];

  protected static function getDefaults() {
    return [
      'available' => 0
    ];
  }

  public function getValue() {
    return $this->available;
  }

  public function setVal($value) {
    $this->available = $value;
  }
}
