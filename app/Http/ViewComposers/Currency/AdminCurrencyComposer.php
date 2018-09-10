<?php

namespace Roomp\Http\ViewComposers\Currency;

use Illuminate\View\View;

class AdminCurrencyComposer {
  public function compose(View $view) {
    $view->with('currencies', config('app.currencies_patterns'));
  }
}