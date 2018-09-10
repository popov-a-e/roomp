<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 30.07.2018
 * Time: 15:51
 */

namespace Roomp\CRS\Periodic\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Validator;

abstract class Model extends BaseModel {
  private $restrictedProperties = ['date', 'room_id'];
  protected $properties = [];
  protected $guarded = ['id'];
  protected $rules = [];

  public function getOrderedProperties() {
    return array_merge($this->restrictedProperties, $this->properties);
  }

  public function getProperties() {
    return $this->properties;
  }

  public function is($model) {
    foreach ($this->getOrderedProperties() as $field) {
      $same = $this->$field == $model->$field;
      if (!$same) return false;
    }

    return true;
  }

  protected static function getDefaults() {
    return [];
  }

  public static function factory(array $data) {
    return (new static)->fill(static::getDefaults())->fill($data);
  }

  private function validate() {
    Validator::make($this->toArray(), $this->rules)->validate();

    return $this;
  }

  public function setValue($value) {
    $this->setVal($value);
    $this->validate();

    return $this;
  }

  abstract public function getValue();
  abstract public function setVal($value);
}