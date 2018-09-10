<?php

namespace Roomp\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeDirectiveServiceProvider extends ServiceProvider {
  private const SHARED_TRANSLATION_CATEGORIES = ['common', 'dates', 'header', 'footer'];

  public function boot() {
    Blade::directive('translations', function($categories) {
      $translations = $this->getTranslations(explode(',', $categories));

      return "<script>window.translations = $translations;</script>";
    });
  }

  private function getTranslations(array $categories) {
    return collect(array_merge(self::SHARED_TRANSLATION_CATEGORIES, $categories))->mapWithKeys(function($key) {
      return [$key => __($key)];
    });
  }

  public function register() {
  }
}
