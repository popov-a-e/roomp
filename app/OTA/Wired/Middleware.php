<?php

namespace Roomp\OTA\Wired;

use Closure;
use Illuminate\Support\Facades\Auth;
use Roomp\Channel;

class Middleware {
  public function handle($request, Closure $next) {
    $user = Channel::where('name', 'Booking')->first();

    Auth::guard('channels')->login($user);

    return $next($request);
  }
}
