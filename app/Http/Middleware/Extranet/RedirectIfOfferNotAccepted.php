<?php

namespace Roomp\Http\Middleware\Extranet;

use Closure;

class RedirectIfOfferNotAccepted {
  public function handle($request, Closure $next) {
    $hotelier = auth('hoteliers')->user();

    if (!($hotelier->hotel->offer ?? false)) {
      //return $next($request);
      //Auth::guard('hoteliers')->logout();
      return redirect('/');
    }

    if (!$hotelier->hotel->offer->accept_date) {
      return redirect('/new_hotel');
    }

    return $next($request);
  }
}
