<?php
/**
 * Created by PhpStorm.
 * User: user1
 * Date: 05.10.2017
 * Time: 13:59
 */

namespace Roomp\Http\Controllers\Admin\Channels;

use Roomp\OTA\Ostrovok\Agent;
use Roomp\OTA\Ostrovok\Repository;

class Ostrovok {
  public function index(Repository $repository) {
    return $repository->getHotels();
  }

  public function refresh(Agent $agent) {
    $agent->updateData();
  }
}