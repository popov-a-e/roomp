<?php

namespace Roomp\Hotels\Organizations;

use Illuminate\Database\Eloquent\Model;

class OrganizationField extends Model {
  protected $table = 'organizations_fields';
  public $timestamps = false;

  public function type() {
    return $this->belongsTo(OrganizationFieldType::class, 'field_id');
  }
}
