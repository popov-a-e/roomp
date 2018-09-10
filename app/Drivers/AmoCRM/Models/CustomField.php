<?php

namespace Roomp\Drivers\AmoCRM\Models;

use Illuminate\Database\Eloquent\Model;
use Roomp\Drivers\AmoCRM\Client;

class CustomField extends Model {
  protected $table = 'amocrm__custom_fields';
  public $timestamps = false;

  private $client;

  public function __construct(array $attributes = []) {
    parent::__construct($attributes);

    $this->client = app()->make(Client::class);
  }

  public function enums() {
    return $this->hasMany(Enum::class);
  }

  public function fillFromCRM(int $id) {
    $fieldName = "f_$id";
    $fieldData = $this->client->getCustomFields()->$fieldName;

    $enums = $fieldData->enums;
    unset($fieldData->enums);

    foreach ($fieldData as $key => $value) {
      $this->$key = $value;
    }

    $this->save();

    foreach ($enums as $enumData) {
      $enum = new Enum();

      $enum->name = $enumData->value;
      $enum->sort = $enumData->sort;
      $enum->roomp_id = 0;
      $enum->amocrm_id = $enumData->id;

      $this->enums()->save($enum)->save();
    }

    $this->save();
  }

  public function download() {
    $id = $this->id;

    $fieldName = "f_$id";
    $fieldData = $this->client->getCustomFields()->$fieldName;

    $enums = collect($fieldData->enums);
    unset($fieldData->enums);

    foreach ($fieldData as $key => $value) {
      $this->$key = $value;
    }

    $this->save();

    $this->enums->each(function($enum) use ($enums) {
      $enumData = $enums->where('value', $enum->name)->first();

      $enum->amocrm_id = $enumData->id;
      $enum->sort = $enumData->sort;

      $enum->save();
    });

    $this->save();
  }

  public function upload() {
    $arr = $this->toArray();

    $arr['enums'] = $this->enums->map(function($enum) {
      return [
        'value' => $enum->name,
        'id' => $enum->amocrm_id,
        'sort' => $enum->sort
      ];
    })->toArray();

    unset($arr['generic_id']);

    $this->client->updateCustomField([
      'action' => 'apply_changes',
      'cf' => [
        'edit' => [$arr]
      ]
    ]);

    $this->download();
  }
}
