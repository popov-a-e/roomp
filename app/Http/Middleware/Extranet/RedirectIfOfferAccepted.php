<?php

namespace Roomp\Http\Middleware\Extranet;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfOfferAccepted {
  public function handle($request, Closure $next) {
    $hotelier = auth('hoteliers')->user();

    if (!$hotelier->hotel->offer) {
      return redirect('/');
    }

    if ($hotelier->hotel->offer->accept_date) {
      return redirect('/');
    }

    return $next($request);
  }
}
