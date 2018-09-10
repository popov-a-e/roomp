<?php

namespace Roomp\Http\ViewComposers\Website;

use Illuminate\View\View;

class ShareConfigs {
  public function compose(View $view) {
    $view->with([
      'locales' => json_encode(app()->locales),
      'currencies' => json_encode(app()->currencies),
    ]);
  }
}