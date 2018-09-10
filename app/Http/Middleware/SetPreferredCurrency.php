<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 27.08.2018
 * Time: 17:07
 */

namespace Roomp\Http\Middleware;

use Closure;

class SetPreferredCurrency {
  public function handle($request, Closure $next) {
    if (session('currency') && in_array(session('currency'), config('app.currencies'))) return $next($request);

    $defaults = [
      'ru' => 'RUB',
      'en' => 'USD',
      'ch' => 'USD',
      'ge' => 'GEL'
    ];

    session(['currency' => $defaults[session('locale')] ?? 'USD']);

    return $next($request);
  }
}