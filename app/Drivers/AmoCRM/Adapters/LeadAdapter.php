<?php
/**
 * Created by PhpStorm.
 * User: user2
 * Date: 22.01.2018
 * Time: 11:26
 */

namespace Roomp\Drivers\AmoCRM\Wrappers;

class LeadAdapter /*extends Adapter*/ {
  public $initial;

  public function __construct($amoLead){
    $this->initial = $amoLead;
  }

  public function initial() {
    return $this->initial;
  }

  public function transformed($AMOHotel) {
    $hotel = (object)[];

    $hotel->regular_name = $AMOHotel->name;
    $hotel->lead_id = $AMOHotel->id;

    $getCustomField = function ($field) use ($AMOHotel) {
      $id = self::ids[$field];
      $fields = $AMOHotel->custom_fields;

      $value = collect($fields)->where('id', $id)->first();

      if (!$value) return null;

      $value = $value->values[0];

      if ($field === 'channel_manager') {
        $cm = $value->enum;

        return isset(self::channel_managers[$cm]) ? self::channel_managers[$cm] : null;
      }

      if ($field === 'city') {
        $city = $value->enum;

        return isset(self::cities[$city]) ? self::cities[$city] : null;
      }

      return $value->value;
    };

    foreach (self::ids as $key => $id) {
      $hotel->$key = $getCustomField($key);
    }

    return $hotel;
  }
}