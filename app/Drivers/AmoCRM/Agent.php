<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 17.01.2018
 * Time: 17:19
 */

namespace Roomp\Drivers\AmoCRM;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Roomp\Drivers\AmoCRM\Adapters\HotelAdapter;
use Roomp\Repositories\HotelRepository;

class Agent {
  const PIPELINE_ID = 835438;
  const ACCEPTED_STAGE_ID = 17040190;

  private $driver, $repository, $hotelRepository;

  public function __construct(Client $driver, Repository $repository, HotelRepository $hotelRepository) {
    $this->driver = $driver;
    $this->repository = $repository;
    $this->hotelRepository = $hotelRepository;
  }

  public function updatePipelineStages() {
    $data = $this->driver->getPipelineData(self::PIPELINE_ID);

    $this->repository->updatePipelineStages($data);
  }

  public function moveLeadToAccepted($leadID) {
    $this->repository->updateLeadStage($leadID, self::ACCEPTED_STAGE_ID);

    $lead = $this->repository->getLeadByID($leadID);

    $this->driver->moveLead($lead->lead_id, $lead->lead_name, self::ACCEPTED_STAGE_ID, time());
    $this->hotelRepository->updateLeadStatus($lead->id, 'active');
  }

  public function handleStageChange($lead, $amoData) {
    $newStage = $this->repository->getStageByID($amoData->status_id);

    $status = $amoData->status_id;

    switch ($newStage->action) {
      case 'return':
        $status = $lead->last_stage_id ?: $amoData->old_status_id;

        $this->driver->moveLead($lead->lead_id, $lead->lead_name, $status, $amoData->last_modified);
        break;
      case 'create':
        $hotel = app()->make(HotelAdapter::class);

        if ($lead->hotel_id) return;

        $hotel->setAmoAttribute($amoData);

        $hotel = $this->hotelRepository->createHotel($hotel->roomp);

        $lead->hotel()->associate($hotel)->save();

        break;
      case 'delete':
        $this->repository->deleteLead($amoData);
        break;
      default:
        if ($newStage->action) {
          $this->repository->moveLeadTo($lead->lead_id, $newStage->action);
        }
        break;
    }

    $this->repository->updateLeadStage($lead->lead_id, $status);
  }

  public function updateHotel($lead, $amoData)  {
    if (!$lead->hotel_id || $lead->stage === 'active' || $lead->stage === 'deleted') return false;

    $hotel = app()->make(HotelAdapter::class);

    $hotel->amo = $amoData;

    $this->hotelRepository->updateHotel($lead->hotel_id, $hotel->roomp);
  }
}