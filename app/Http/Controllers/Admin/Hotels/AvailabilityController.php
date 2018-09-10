<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Roomp\CRS\Periodic\Availability;

class AvailabilityController extends PeriodController {
  public function __construct(Availability $availability) {
    parent::__construct($availability);
  }
}
