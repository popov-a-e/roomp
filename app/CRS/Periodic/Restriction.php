<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 19.07.2018
 * Time: 17:15
 */

namespace Roomp\CRS\Periodic;

use Roomp\CRS\Periodic\Models\Restriction as Model;

class Restriction extends Period {
  use ChecksIfOpen;

  protected $modelClass = Model::class;
  protected $rules = [
    'rate_id' => 'integer|min:1'
  ];

  public function setRate(int $rateID) {
    $this->setParam('rate_id', $rateID);

    return $this;
  }
}