<?php

namespace Roomp\Http\ViewComposers\Website;

use Illuminate\View\View;

class ShareUser {
  private function getUser() {
    return json_encode(
      auth('users')->user()
    );
  }

  private function getCurrency() {
    return auth('users')->user()->currency ??
      session('currency');
  }

  public function compose(View $view) {
    $view->with([
      'user' => $this->getUser(),
      'currency' => $this->getCurrency(),
    ]);
  }
}