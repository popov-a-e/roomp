<?php

namespace Roomp\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  public function boot() {
    Resource::withoutWrapping();

    Relation::morphMap([
      'users' => 'Roomp\User',
      'hoteliers' => 'Roomp\Hotelier',
      'admins' => 'Roomp\Admin',
      'channels' => 'Roomp\Channel'
    ]);

    app()->locales = config('app.locales');
    app()->currencies = config('app.currencies');
  }

  public function register() {
  }
}
