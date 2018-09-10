<?php

namespace Roomp\Providers;

use Illuminate\Support\ServiceProvider;
use Roomp\Http\ViewComposers\Currency\AdminCurrencyComposer;
use Roomp\Http\ViewComposers\AdminLocaleComposer;
use Roomp\Http\ViewComposers\Currency\WebsiteCurrencyComposer;
use Roomp\Http\ViewComposers\LoginLocaleComposer;
use Roomp\Http\ViewComposers\ShareLocale;
use Roomp\Http\ViewComposers\User\ShareUserWebsite;
use Roomp\Http\ViewComposers\Website\ShareConfigs;
use Roomp\Http\ViewComposers\Website\ShareUser;

class ViewServiceProvider extends ServiceProvider {
  private $webViews = [
    'website.*.index',
    'website.static.layout'
  ];

  public function boot() {/*
    view()->composer('admin.index.index', AdminLocaleComposer::class);
    view()->composer('admin.index.index', AdminCurrencyComposer::class);
    view()->composer('extranet.layout', '');

    view()->composer('*.login.index', LoginLocaleComposer::class);
*/

    view()->composer('admin.index.index', AdminCurrencyComposer::class);

    view()->composer($this->webViews, ShareUser::class);
    view()->composer($this->webViews, ShareConfigs::class);

    view()->composer('*', ShareLocale::class);
  }

  public function register() {
  }
}
