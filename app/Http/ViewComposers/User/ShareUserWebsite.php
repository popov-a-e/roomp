<?php

namespace Roomp\Http\ViewComposers\User;

use Illuminate\View\View;

class ShareUserWebsite {
  public function compose(View $view) {
    $view->with([
      'user' => auth('users')->user(),
      'currency' => session('currency') ?? auth('users')->user()->currency ?? 'RUB'
    ]);
  }
}