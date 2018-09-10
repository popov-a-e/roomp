<?php

namespace Roomp\Http\Middleware;

use Closure;

class RedirectIfAuthenticated {
  public function handle($request, Closure $next, $guard = null) {
    if (auth($guard)->check())
      return redirect('/');

    return $next($request);
  }
}
