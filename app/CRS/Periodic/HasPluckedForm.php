<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 25.07.2018
 * Time: 13:46
 */

namespace Roomp\CRS\Periodic;

trait HasPluckedForm {
  public function plucked() {
    return $this->collect()->mapWithKeys(function ($model) {
      return [
        $model->date => $model->getValue()
      ];
    })->toArray();
  }

  public function pluck() {
    return $this->plucked();
  }
}