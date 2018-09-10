<?php

namespace Roomp\OTA\Ostrovok;

use Closure;
use Illuminate\Support\Facades\Auth;
use Roomp\Channel;

class Middleware {
  public function handle($request, Closure $next) {
    Auth::guard('channels')->login(Channel::where('name', 'Ostrovok')->first());

    return $next($request);
  }
}
