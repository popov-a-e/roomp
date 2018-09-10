<?php

namespace Roomp\Http\Controllers\Admin\Hotels;

use Roomp\CRS\Periodic\Restriction;

class RestrictionsController extends PeriodController {
  public function __construct(Restriction $restriction) {
    parent::__construct($restriction);
  }
}
