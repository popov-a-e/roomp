<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.08.2018
 * Time: 17:07
 */

namespace Roomp\Http\Middleware\Admin;

use Closure;

class AdminLocaleMiddleware {
  public function handle($request, Closure $next) {
    app()->setLocale('ru');

    return $next($request);
  }
}