<?php

namespace Roomp\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Facades\Agent;

class DeviceDetector {
  public function handle($request, Closure $next) {
    session([
      'agent' => [
        'mobile' => $request->input('mobile') || Agent::isMobile(),
        'ios' => Agent::is('iOS'),
        'safari' => Agent::browser() === 'Safari',
      ]
    ]);

    return $next($request);
  }
}
