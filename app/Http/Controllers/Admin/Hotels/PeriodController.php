<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 13.08.2018
 * Time: 14:15
 */

namespace Roomp\Http\Controllers\Admin\Hotels;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Roomp\CRS\Periodic\Period;
use Roomp\Hotels\Hotel;

abstract class PeriodController {
  protected $restrictions;

  public function __construct(Period $period) {
    $this->period = $period;
  }

  public function get(Hotel $hotel, Request $request) {
    return $this->period
      ->from(Carbon::parse($request->from))
      ->till(Carbon::parse($request->till))
      ->atRooms($hotel->rooms()->pluck('id')->toArray())
      ->group();
  }
}