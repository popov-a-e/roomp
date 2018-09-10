<?php

namespace Roomp\Drivers\AmoCRM\Adapters;
use Roomp\Drivers\AmoCRM\Repository;
use stdClass as object;

/**
 * Created by PhpStorm.
 * User: user2
 * Date: 22.01.2018
 * Time: 11:39
 */
interface AdapterInterface {
  const FIELDS = [
    'city' => Repository::FIELD_CITY,
    'address' => Repository::FIELD_ADDRESS,
    'channel_manager' => Repository::FIELD_CHANNEL_MANAGER,
    'name' => Repository::FIELD_NAME,
    'reception_phone' => Repository::FIELD_RECEPTION_PHONE,
    'reception_email' => Repository::FIELD_RECEPTION_EMAIL,
    'room_number' => Repository::FIELD_ROOM_NUMBER,
    'dynamic_roomp_rate_discount' => Repository::FIELD_DISCOUNT_RATE,
  ];

  public function getAmoAttribute() : object;
  public function setAmoAttribute(object $value);
  public function getRoompAttribute() : object;
  public function setRoompAttribute(object $value);
}

abstract class Adapter implements AdapterInterface {
  protected $repository;

  public function __construct(Repository $repository) {
    $this->repository = $repository;
  }

  protected function getValue($customFields, $customFieldName) {
    $field = collect($customFields)->where('id', self::FIELDS[$customFieldName])->first();

    if (!$field) return null;

    return $field->values[0]->value;
  }

  protected function  getEnumValue($customFields, $customFieldName) {
    $field = collect($customFields)->where('id', self::FIELDS[$customFieldName])->first();

    if (!$field) return null;

    return $this->getEnumRoompID($field->id, $field->values[0]->enum ?? null);
  }

  protected function getEnumRoompID($fieldID, $amoID) {
    $enum = $this->repository->getEnum($fieldID, $amoID);

    return $enum->roomp_id ?? null;
  }

  public function __get($param) {
    if ($param === 'amo') return $this->getAmoAttribute();
    if ($param === 'roomp') return $this->getRoompAttribute();

    return null;
  }

  public function __set($param, $value) {
    if ($param === 'amo') return $this->setAmoAttribute($value);
    if ($param === 'roomp') return $this->setRoompAttribute($value);
  }
}