<?php
/**
 * Created by PhpStorm.
 * User: user2
 * Date: 22.01.2018
 * Time: 14:44
 */

namespace Roomp\Drivers\AmoCRM\Listeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Roomp\Drivers\AmoCRM\Client;
use Roomp\Drivers\AmoCRM\Repository;
use Roomp\Events\CityCreatedEvent;

class CityCreatedListener implements ShouldQueue {
  private $repository, $client;

  public function __construct(Repository $repository, Client $client) {
    $this->repository = $repository;
    $this->client = $client;
  }

  public function handle(CityCreatedEvent $event) {
    $customField = $this->repository->updateCustomField($event->cities, $this->repository::FIELD_CITY);
    $customField->upload();
  }
}