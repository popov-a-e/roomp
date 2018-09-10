<?php

namespace Roomp\Drivers\AmoCRM\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Roomp\Drivers\AmoCRM\Agent;
use Roomp\Drivers\AmoCRM\Client;
use Roomp\Drivers\AmoCRM\Repository;

class Controller extends BaseController {
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  private $repository, $client, $agent;

  public function __construct(Repository $repository, Client $client, Agent $agent) {
    $this->repository = $repository;
    $this->client = $client;
    $this->agent = $agent;
  }

  public function endpoint(Request $request) {
    $data = $request->all();

    foreach ($data as $subject => $arr) {
      foreach ($arr as $action => $actionDataArray) {
        foreach ($actionDataArray as $rawData) {
          $this->$action($subject, json_decode(json_encode($rawData)));
        }
      }

      break;
    }
  }

  private function add($subject, $rawData) {
    switch ($subject) {
      case 'leads':
        $this->addLead($rawData);
        break;
      case 'contacts':
        $contact = $this->client->getContactData($rawData->id);
        $lead = $this->repository->getLeadByID($contact->leads->id[0]);
        if (!$lead) {
          $lead = $this->addLead($rawData);
          break;
        }
        $this->repository->addContact($lead, $contact);

        break;
    }
  }

  private function addLead($rawData) {
    $lead = $this->repository->addLead($rawData);

    if ($this->client->getLeadData($rawData->id)->contacts->id[0] ?? false) {
      $contactID = $this->client->getLeadData($rawData->id)->contacts->id[0];
      $contact = $this->client->getContactData($contactID);
      $this->repository->addContact($lead, $contact);
    }

    return $lead;
  }

  private function update($subject, $rawData) {
    switch ($subject) {
      case 'leads':
        $lead = $this->repository->updateLead($rawData);

        if (!$lead) $lead = $this->addLead($rawData);

        if (
          isset($rawData->old_status_id)
          && 1 * $lead->last_stage_id !== 1 * $rawData->status_id
          && $this->agent::PIPELINE_ID === 1 * $rawData->pipeline_id
        ) {
          $this->agent->handleStageChange(
            $lead,
            $rawData
          );
        }

        $this->agent->updateHotel($lead, $rawData);

        break;
      case 'contacts':
        $this->repository->updateContact($rawData);
        break;
    }
  }

  private function delete($subject, $actionData) {
    switch ($subject) {
      case 'leads':
        $this->repository->deleteLead($actionData);
        break;
      case 'contacts':
        $this->repository->deleteContact($actionData->id);
        break;
    }
  }
}
