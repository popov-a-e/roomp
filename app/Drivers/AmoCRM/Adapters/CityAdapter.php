<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 25.12.2017
 * Time: 0:01
 */

namespace Roomp\Drivers\AmoCRM\Wrappers;

class CityAdapter /*implements Adapter */{
  private $_amo, $_roomp;

  public function setAmoAttribute(object $value) {
    $this->_amo = $value;
  }

  public function setRoompAttribute(object $value) {
    $this->_roomp = $value;
  }

  public function getAmoAttribute(): object {
    if (!$this->_roomp) return null;


  }

  public function getRoompAttribute(): object {
    if (!$this->_amo) return null;

    $amo = $this->_amo;
    $roomp = (object)[];

    $roomp->ru = $amo->name;
    $roomp->name = $amo->name;
    $roomp->id = $amo->roomp_id;
    $roomp->amo_id = $amo->amocrm_id;

    return $roomp;
  }
}