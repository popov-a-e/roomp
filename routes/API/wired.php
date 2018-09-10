<?php

use Roomp\CM\Wubook\Middleware as WuBookMiddleware;
use Roomp\Drivers\OTA\Wubook\Middleware as WubookWiredMiddleware;
use Illuminate\Support\Facades\Route;

Route::any('wubook/push', '\Roomp\Drivers\OTA\Wubook\Controller@push');
