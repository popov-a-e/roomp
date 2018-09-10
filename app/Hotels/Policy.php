<?php

namespace Roomp\Hotels;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model {
  public $timestamps = false;
  protected $casts = [
    'last_minute' => 'object'
  ];
  protected $fillable = [
    'price_children',
    'price_adults',
    'price_infants',
    'bed_number',
    'age_children',
    'last_minute',
    'breakfast_price',
  ];

  public function getLastMinutesAttribute() {
    return collect($this->last_minute)->sortKeys()->toArray();
  }
}
