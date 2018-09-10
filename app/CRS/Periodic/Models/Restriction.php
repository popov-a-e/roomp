<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 31.07.2018
 * Time: 15:34
 */

namespace Roomp\CRS\Periodic\Models;


class Restriction extends Model {
  protected $table = 'restrictions';
  public $timestamps = false;

  protected $casts = [
    'closed' => 'boolean',
    'closed_to_arrival' => 'boolean',
    'closed_to_departure' => 'boolean',
    'minstay' => 'integer',
    'maxstay' => 'integer',
    'minstay_on_arrival' => 'integer'
  ];

  protected $rules = [
    'closed' => 'boolean',
    'closed_to_arrival' => 'boolean',
    'closed_to_departure' => 'boolean',
    'minstay' => 'integer|min:0',
    'maxstay' => 'integer|min:0',
    'minstay_on_arrival' => 'integer|min:0'
  ];

  protected $properties = ['rate_id'];

  private function getValueKeys() {
    return array_keys($this->casts);
  }
  protected static function getDefaults() {
    return [
      'rate_id' => 1,
      'closed' => false,
      'closed_to_arrival' => false,
      'closed_to_departure' => false,
      'minstay' => 0,
      'maxstay' => 0,
      'minstay_on_arrival' => 0
    ];
  }

  public function getValue() {
    return $this->only($this->getValueKeys());
  }

  public function setVal($restrictions) {
    foreach ($restrictions as $key => $value) {
      $this->$key = $value;
    }
  }
}