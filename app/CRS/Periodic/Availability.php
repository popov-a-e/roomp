<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 19.07.2018
 * Time: 15:57
 */

namespace Roomp\CRS\Periodic;

use Roomp\CRS\Periodic\Models\Availability as Model;

class Availability extends Period {
  protected $modelClass = Model::class;
}