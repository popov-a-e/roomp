<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 23.07.2018
 * Time: 16:12
 */

namespace Roomp\CRS\Periodic;

use Illuminate\Support\Facades\Validator as LaravelValidator;

class Validator {
  private $rules = [];

  public function validate(array $validatable) {
    LaravelValidator::make($validatable, $this->rules)->validate();

    return $this;
  }

  public function validatePresence(array $validatable) {
    LaravelValidator::make($validatable, $this->getPresenceRules())->validate();

    return $this;
  }

  private function getPresenceRules() {
    return array_map(function() {return 'required';}, $this->rules);
  }

  public function setRules(array $rules) {
    $this->rules = $rules;
  }
}