<?php

namespace Roomp\Hotels\Organizations;

use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model {
  protected $table = 'contact_faces';
  protected $fillable = [
    'name', 'email', 'position', 'phone'
  ];

  public function organization() {
    return $this->belongsTo(Organization::class);
  }
}
