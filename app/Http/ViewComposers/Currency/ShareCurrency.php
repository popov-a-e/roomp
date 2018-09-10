<?php

namespace Roomp\Http\ViewComposers\Currency;

use Illuminate\View\View;

class ShareCurrency {
  public function compose(View $view) {
    $view->with([
      'locale' => app()->getLocale()
    ]);
  }
}