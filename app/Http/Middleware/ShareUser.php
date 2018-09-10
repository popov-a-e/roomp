<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.08.2018
 * Time: 17:07
 */

namespace Roomp\Http\Middleware;

use Closure;

class ShareUser {
  public function handle($request, Closure $next, $guard = null) {
    view()->share('user', auth($guard)->user());

    return $next($request);
  }
}