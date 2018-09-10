<?php

namespace Roomp\Providers;

use Illuminate\Support\ServiceProvider;
use Roomp\CRS\Periodic\Availability;
use Roomp\CRS\Periodic\ChannelPrice;
use Roomp\CRS\Periodic\Price;
use Roomp\CRS\Periodic\Restriction;

class PeriodicServiceProvider extends ServiceProvider {
  public function boot() {
    $this->app->bind('Price', Price::class);
    $this->app->bind('ChannelPrice', ChannelPrice::class);
    $this->app->bind('Availability', Availability::class);
    $this->app->bind('Restriction', Restriction::class);
  }

  public function register() {
  }
}
