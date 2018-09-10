<?php

namespace Roomp\Drivers\AmoCRM\Models;

use Illuminate\Database\Eloquent\Model;

class Enum extends Model {
  public $timestamps = false;
  protected $table = 'amocrm__custom_field_enums';
  protected $guarded = [];

  public function field() {
    return $this->belongsTo(CustomField::class);
  }
}
