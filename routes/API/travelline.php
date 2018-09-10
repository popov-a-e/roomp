<?php

use Roomp\CM\Travelline\Middleware as TravellineMiddleware;
use Illuminate\Support\Facades\Route;

$routes = function() {
  Route::middleware(TravellineMiddleware::class)->prefix('v1')->group(function() {
    Route::any('/travelline', '\Roomp\CM\Travelline\Controller@endpoint');
  });
};
