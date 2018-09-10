<?php

namespace Roomp\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Symfony\Component\VarDumper\Caster\DOMCaster;

class RouteServiceProvider extends ServiceProvider {
  protected $namespace = 'Roomp\Http\Controllers';

  private const DOMAINS = [
    'site' => [
      'roomp.co',
      'www.roomp.co',
      'draft.roomp.co',
      'main.simon.dev.roomp.co',
    ],
    'admin' => [
      'admin.roomp.co',
      'draftadmin.roomp.co',
      'admin.simon.dev.roomp.co',
    ],
    'extranet' => [
      'extranet.roomp.co',
      'draftextranet.roomp.co',
      'extranet.simon.dev.roomp.co'
    ],
    'api' => [
      'api.roomp.co',
      'draft.roomp.co',
      'draftapi.roomp.co',
      'api.simon.dev.roomp.co'
    ]
  ];

  public function boot() {
    parent::boot();
  }

  public function map() {
    $this->mapApiRoutes();
    $this->mapWebRoutes();
  }

  protected function mapWebRoutes() {
    foreach(self::DOMAINS['site'] as $domain)
    Route::middleware('web')
      ->domain($domain)
      ->namespace($this->namespace . '\Website')
      ->group(base_path('routes/web/website/web.php'));

    foreach(self::DOMAINS['admin'] as $domain) {
      Route::middleware(['web','admin'])
        ->namespace($this->namespace.'\Admin')
        ->domain($domain)
        ->group(base_path('routes/web/admin/web.php'));
    }
    /*

        Route::middleware('web')
          ->namespace($this->namespace . '\Admin')
          ->group(base_path('routes/admin/web.php'));*/
  }

  protected function mapApiRoutes() {
    Route::middleware('api')->prefix('v1')->group(function () {
      foreach (self::DOMAINS['api'] as $domain) {
        Route::domain($domain)->group(function() {
          Route::namespace('OTA')->group(function() {
            Route::middleware(WiredMiddleware::class)->namespace('Wired')->group(base_path('routes/API/wired.php'));
            Route::middleware(OstrovokMiddleware::class)->namespace('Ostrovok')->group(base_path('routes/API/ostrovok.php'));
          });

          Route::namespace('API')->group(function() {
            Route::middleware(WoodooMiddleware::class)->namespace('Woodoo')->group(base_path('routes/API/woodoo.php'));
            Route::middleware(TravellineMiddleware::class)->namespace('Travelline')->group(base_path('routes/API/travelline.php'));
          });

          Route::prefix('tinkoff')->namespace('Roomp\PaymentGates\Tinkoff')->group(base_path('routes/API/tinkoff.php'));
          Route::prefix('amo')->namespace('Roomp\Drivers\AmoCRM')->group(base_path('routes/API/amocrm.php'));
        });
      }
    });
  }
}
