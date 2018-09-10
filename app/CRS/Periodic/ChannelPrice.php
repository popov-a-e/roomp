<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 19.07.2018
 * Time: 17:15
 */

namespace Roomp\CRS\Periodic;

use Roomp\CRS\Periodic\Models\ChannelPrice as Model;

class ChannelPrice extends Period {
  protected $modelClass = Model::class;
  protected $rules = [
    'rate_id' => 'integer|min:1',
    'guest_number' => 'integer|min:1'
  ];

  public function setGuestNumber(int $guestNumber) {
    $this->setParam('guest_number', $guestNumber);

    return $this;
  }

  public function setRate(int $rateID) {
    $this->setParam('rate_id', $rateID);

    return $this;
  }
}