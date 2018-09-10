<?php

namespace Roomp\Http\Middleware\Extranet;

use Closure;
use Illuminate\Support\Facades\App;
use Jenssegers\Agent\Facades\Agent;

class ExtranetLocaleMiddleware {
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \Closure $next
   * @return mixed
   */
  public function handle($request, Closure $next) {
    $user = auth('hoteliers')->user();

    if ($user) {
      session(['locale' => $user->locale]);
    }
/*
    $availableLocales = config('app.available_locales');
    $availableCurrencies = config('app.available_currencies');

    if (!session('locale') || !collect($availableLocales)->contains(session('locale'))) {
      $lang = mb_strtolower($request->header('accept-language'));

      $lang = explode(';',$lang);

      try {
        foreach ($lang as $l) {
          foreach ($availableLocales as $a) {
            if (strrpos($l, $a) !== false) {
              session(['locale' => $a]);

              throw new \Exception();
            }
          }
        }

        session(['locale' => 'en']);
      } catch (\Exception $e) {
      }
    }

    if (!session('currency') || !collect($availableCurrencies)->contains(session('currency'))) {
      $defaults = [
        'ru' => 'RUB',
        'en' => 'USD',
        'ch' => 'USD'
      ];

      session(['currency' => $defaults[session('locale')]]);
    }*/

    app()->setLocale(session('locale'));

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
