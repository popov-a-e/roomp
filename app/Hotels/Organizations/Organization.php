<?php

namespace Roomp\Hotels\Organizations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Roomp\Hotels\Hotel;

class Organization extends Model {
  public function contacts() {
    return $this->hasMany(ContactPerson::class)->orderBy('id');
  }

  public function hotels() {
    return $this->hasMany(Hotel::class, 'organization_id', 'id');
  }

  public function fields() {
    return $this->hasMany(OrganizationField::class);
  }

  public function fillLocal(Hotel $hotel, array $attributes) {
    foreach ($this->localFields as $field) {
      $field->field_value = $attributes[$field->type->name] ?? null;

      $field->save();
    }

    $hotel->organization()->associate($this)->save();

    return $this;
  }

  public function getLocalFieldsAttribute() {
    $fields = OrganizationFieldType::where('locale', $this->locale)->get();

    $localFields =  $this->fields()->with('type')->whereHas('type', function($q) {
      $q->where('locale', $this->locale);
    })->get();

    foreach($fields as $field) {
      if ($localFields->where('field_id', $field->id)->count() === 0) {
        $f = new OrganizationField();

        $f->organization_id = $this->id;
        $f->field_id = $field->id;
        $f->field_value = null;

        $localFields->push($f);
      }
    }

    return $localFields->sortBy('type.id')->sortBy('type.order');
  }

  public function createEmptyOrganization($locale) {
    $fields = OrganizationFieldType::where('locale', $locale)->get();

    $toInsert = [];

    foreach($fields as $type) {
      $toInsert[] = [
        'organization_id' => $this->id,
        'field_id' => $type->id,
        'field_value' => null
      ];
    }

    DB::table('organizations_fields')->insert($toInsert);
  }

  public function getLocalAttribute() {
    $local = (object)[];
    $fieldValues = $this->fields()->with('type')->orderBy('id')->get();

    $fields = OrganizationFieldType::where('locale', $this->locale)->get();

    foreach ($fields as $field) {
      if ($field->locale !== $this->locale) continue;
      $local->{$field->name} = $fieldValues->where('field_id', $field->id)->first()->field_value ?? null;
    }

    return $local;
  }
}
