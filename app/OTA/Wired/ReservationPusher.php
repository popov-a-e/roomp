<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 03.08.2017
 * Time: 15:37
 */

namespace Roomp\OTA\Wired;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Roomp\Channel;
use Roomp\Events\WubookReservationPushed;
use Roomp\Repositories\ReservationRepository;

class ReservationPusher implements ShouldQueue {
  private $auth;
  private $channel;
  private $repository;

  public function __construct(Repository $repository, Channel $channel, Auth $auth) {
    $this->auth = $auth;
    $this->channel = $channel;
    $this->repository = $repository;
  }

  public function handle(WubookReservationPushed $event) {
    //try {
    dd (1);
      $this->repository->handleReservation($event->lcode, $event->rcode);
      file_put_contents('/var/log/roomp/wubook.log', "{$event->lcode}, {$event->rcode}"."\r\n", FILE_APPEND);
    /*} catch (\Exception $exception) {
      dd ($exception);
      file_put_contents('/var/log/roomp/wubook.log', "{$event->lcode}, {$event->rcode} : ".$exception->getMessage()."\r\n\r\n", FILE_APPEND);
    }*/
  }
}