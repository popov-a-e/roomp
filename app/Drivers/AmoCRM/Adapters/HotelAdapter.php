<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 25.12.2017
 * Time: 0:01
 */

namespace Roomp\Drivers\AmoCRM\Adapters;

use Roomp\Drivers\AmoCRM\Repository;
use Roomp\Drivers\Yandex\GeocodeAPI;
use stdClass as object;

class HotelAdapter extends Adapter {
  private $_amo, $_roomp, $geoAPI;

  public function __construct(Repository $repository, GeocodeAPI $API) {
    parent::__construct($repository);
    $this->geoAPI = $API;
  }

  public function setAmoAttribute(object $amo) {
    $this->amo = $amo;

    $hotel = (object)[];

    $hotel->regular_name = $amo->name;

    $customFields = $amo->custom_fields;

    $hotel->city_id = $this->getEnumValue($customFields, 'city');
    $hotel->channel_id = $this->getEnumValue($customFields, 'channel_manager');
    $hotel->reception_email = $this->getValue($customFields, 'reception_email');
    $hotel->reception_phone = $this->getValue($customFields, 'reception_phone');

    $hotel->room_number = $this->getValue($customFields, 'room_number');
    $hotel->dynamic_roomp_rate_discount = $this->getValue($customFields, 'dynamic_roomp_rate_discount');
    $hotel->dynamic_roomp_rate = !!$hotel->dynamic_roomp_rate_discount;

    $hotel->address_ru = $this->getValue($customFields, 'address');
    $hotel->ru = $this->getValue($customFields, 'name') ?? "Roomp {$amo->name}";
    [$lat, $lng] = $this->geoAPI->getLatLngByAddress($hotel->address_ru);

    $hotel->lat = $lat ?: 0.0;
    $hotel->lng = $lng ?: 0.0;

    $hotel->organization_id = $this->repository->getLeadByID($amo->id)->organization_id;

    $hotel->pretty_url = str_random(64);

    $this->_roomp = $hotel;
  }

  public function setRoompAttribute(object $roomp) {
    $this->_roomp = $roomp;
  }

  public function getAmoAttribute() : object {
    return $this->_amo;
  }

  public function getRoompAttribute() : object {
    return $this->_roomp;
  }
}