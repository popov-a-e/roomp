<?php

namespace Roomp\Http\ViewComposers;

use Illuminate\View\View;

class ShareLocale {
  public function compose(View $view) {
    $view->with([
      'locale' => app()->getLocale()
    ]);
  }
}