<?php

namespace Roomp\Drivers\AmoCRM\Models;

use Illuminate\Database\Eloquent\Model;
use Roomp\Hotels;
use Roomp\Organization;

class Lead extends Model {
  protected $table = 'amocrm__leads';
  public $timestamps = false;

  protected $guarded = [];

  public function organization() {
    return $this->belongsTo(Organization::class);
  }

  public function hotel() {
    return $this->belongsTo(Hotel::class);
  }

  protected static function boot() {
    parent::boot();

    static::created(function($lead) {
      $organization = new Organization();

      $organization->save();

      $lead->organization()->associate($organization);

      $lead->save();
    });
  }
}
