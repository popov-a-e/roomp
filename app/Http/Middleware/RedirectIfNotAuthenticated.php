<?php

namespace Roomp\Http\Middleware ;

use Closure;

class RedirectIfNotAuthenticated {
  public function handle($request, Closure $next, $guard = null) {
    if (!auth('hoteliers')->check()) {
      return redirect('/login');
    }
    return $next($request);
  }
}
