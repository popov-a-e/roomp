<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 17.01.2018
 * Time: 17:18
 */

namespace Roomp\Drivers\AmoCRM;

use Roomp\ContactPerson;
use Roomp\Drivers\AmoCRM\Models\CustomField;
use Roomp\Drivers\AmoCRM\Models\Enum;
use Roomp\Drivers\AmoCRM\Models\Lead;
use Roomp\Drivers\AmoCRM\Models\Stage;

class Repository {
  public const FIELD_CITY = 1968481;
  public const FIELD_ADDRESS = 1972179;
  public const FIELD_CHANNEL_MANAGER = 1972173;
  public const FIELD_NAME = 1970639;
  public const FIELD_CONTACT_PHONE = 1263814;
  public const FIELD_CONTACT_EMAIL = 1263816;
  public const FIELD_CONTACT_POSITION = 1263812;
  public const FIELD_RECEPTION_PHONE = 1976253;
  public const FIELD_RECEPTION_EMAIL = 1976251;
  public const FIELD_ROOM_NUMBER = 1972167;
  public const FIELD_DISCOUNT_RATE = 1972175;

  public function __construct() {
  }

  public function getStageByID($id) {
    return Stage::where('stage_id', $id)->first();
  }

  public function getLeadByID($id) {
    return Lead::where('lead_id', $id)->first();
  }

  public function getEnum($fieldID, $amoID) {
    return Enum::where('custom_field_id', $fieldID)->where('amocrm_id', $amoID)->first();
  }

  public function updatePipelineStages($data) {
    collect($data->statuses)->values()->sortBy('sort')->each(function ($stage) {
      $roompStage = Stage::where('stage_id', $stage->id)->first();

      if (!$roompStage) {
        $roompStage = new Stage();

        $roompStage->stage_id = $stage->id;
      }

      $roompStage->stage_name = $stage->name;
      $roompStage->save();
    });
  }

  public function updateCustomField(array $fieldset, int $fieldID) {
    $amoFields = Enum::where('custom_field_id', $fieldID)->get();

    foreach ($fieldset as $field) {
      $amoField = $amoFields->where('roomp_id', $field->id)->first();

      if (!$amoField) $amoField = new Enum([
        'custom_field_id' => $fieldID,
        'sort' => $amoFields->max('sort') + 1
      ]);

      $amoField->name = $field->ru;
      $amoField->roomp_id = $field->id;

      $amoField->save();
    }

    return CustomField::where('id', $fieldID)->with('enums')->first();
  }

  public function addLead($leadData) {
    $lead = new Lead([
      'lead_id' => $leadData->id,
      'lead_name' => $leadData->name,
      'business_developer_id' => $leadData->responsible_user_id,
      //'last_stage_id' => $leadData->status_id
    ]);

    $lead->save();

    return $lead;
  }

  public function addContact($lead, $contactData) {
    if (!isset($contactData->custom_fields)) return false;

    $fields = collect($contactData->custom_fields);

    $position = $fields->where('id', self::FIELD_CONTACT_POSITION)->first();
    $phone = $fields->where('id', self::FIELD_CONTACT_PHONE)->first();
    $email = $fields->where('id', self::FIELD_CONTACT_EMAIL)->first();

    $contact = new ContactPerson([
      'organization_id' => $lead->organization_id,
      'name' => $contactData->name,
      'position' => $position ? $position->values[0]->value : null,
      'phone' => $phone ? $phone->values[0]->value : null,
      'email' => $email ? $email->values[0]->value : null,
      'amocrm_id' => $contactData->id
    ]);

    $lead->organization->contacts()->save($contact)->save();
  }

  public function updateContact($contactData) {
    $contact = ContactPerson::where('amocrm_id', $contactData->id)->first();

    if (!$contact) return false;

    if (!isset($contactData->custom_fields)) return false;

    $fields = collect($contactData->custom_fields);

    $position = $fields->where('id', self::FIELD_CONTACT_POSITION)->first();
    $phone = $fields->where('id', self::FIELD_CONTACT_PHONE)->first();
    $email = $fields->where('id', self::FIELD_CONTACT_EMAIL)->first();

    $contact->phone = $phone ? $phone->values[0]->value : null;
    $contact->email = $email ? $email->values[0]->value : null;
    $contact->position = $position ? $position->values[0]->value : null;

    $contact->name = $contactData->name;

    $contact->save();
  }

  public function updateLead($leadData) {
    $lead = Lead::where('lead_id', $leadData->id)->first();

    if (!$lead) return false;

    $lead->lead_name = $leadData->name;
    $lead->business_developer_id = $leadData->responsible_user_id;
    //$lead->last_stage_id = $leadData->status_id;

    $lead->save();

    return $lead;
  }

  public function moveLeadTo($leadID, $stage) {
    $lead = Lead::where('lead_id', $leadID)->first();

    $lead->stage = $stage;

    $lead->save();

    $hotel = $lead->hotel;
    $hotel->status = $stage;
    $hotel->save();
  }

  public function updateLeadStage($leadID, $stageID) {
    Lead::where('lead_id', $leadID)->update(['last_stage_id' => $stageID]);
  }

  public function deleteLead($leadData) {
    $lead = Lead::where('lead_id', $leadData->id)->first();

    if (!$lead) return false;

    $lead->stage = 'deleted';

    $hotel = $lead->hotel;
    $hotel->status = 'deleted';
    $hotel->save();

    $lead->save();
  }

  public function deleteContact($contactID) {
    $contact = ContactPerson::where('amocrm_id', $contactID)->first();

    if (!$contact) return false;

    $contact->delete();
  }

}