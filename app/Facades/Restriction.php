<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 30.08.2018
 * Time: 18:45
 */

namespace Roomp\Facades;

use Illuminate\Support\Facades\Facade;

class Restriction extends Facade {
  protected static function getFacadeAccessor() {
    return 'Restriction';
  }
}