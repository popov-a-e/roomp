<?php

use Roomp\Drivers\OTA\Ostrovok\Middleware as OstrovokMiddleware;
use Illuminate\Support\Facades\Route;

Route::domain('api.roomp.co')->middleware(OstrovokMiddleware::class)->group(function() {
  Route::any('push', 'API\ReservationPushController@ostrovok');
});