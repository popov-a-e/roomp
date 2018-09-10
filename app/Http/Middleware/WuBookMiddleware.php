<?php

namespace Roomp\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\UuidFactory;

class WuBookMiddleware {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */

  public function handle($request, Closure $next) {
    $this->checkCM($request->input('cm_auth.login'), $request->input('cm_auth.password'));

    $this->attemptLogin($request->input('hotel_auth.login') ?: $request->input('hotel_auth.email'), $request->input('hotel_auth.password'));

    return $next($request);
  }

  private function attemptLogin($email, $password) {
    $loginSuccessful = Auth::guard('hoteliers')->attempt(compact('email','password'));

    if (!$loginSuccessful) {
      abort(402, 'Invalid hotel credentials');
    }
  }

  private function checkCM($login, $password) {
    $loginSuccessful = Auth::guard('api')->attempt(compact('login','password'));

    if (!$loginSuccessful) {
      abort(402, 'Invalid CM credentials');
    }
  }
}
