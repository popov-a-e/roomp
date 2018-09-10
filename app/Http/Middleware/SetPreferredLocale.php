<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.08.2018
 * Time: 17:07
 */

namespace Roomp\Http\Middleware;

use Closure;

class SetPreferredLocale {
  public function handle($request, Closure $next) {
    app()->setLocale($request->getPreferredLanguage(config('app.locales')));

    return $next($request);
  }
}