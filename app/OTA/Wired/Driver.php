<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 28.07.2017
 * Time: 15:47
 */

namespace Roomp\OTA\Wired;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Roomp\Drivers\XMLRPC\Server;

function fdate($date) {
  if ($date instanceof Carbon) {
    return $date->format('d/m/Y');
  } else {
    return Carbon::parse($date)->format('d/m/Y');
  }
}

class Driver {
  private $server;

  private $username;
  private $password;
  private $provider_key;
  private $pushURL;
  private $lcode;

  private $token;

  private $fails = 0;

  private const BOOKING = 2;

  public function __construct(Server $server) {
    $this->server = $server;
    Carbon::setLocale('us');

    $this->username = config('wubook.cm.username');
    $this->password = config('wubook.cm.password');
    $this->provider_key = config('wubook.cm.provider_key');
    $this->pushURL = config('wubook.cm.push_url');
    $this->lcode = config('wubook.cm.lcode');

    $this->token = Redis::get('wubook_cm:token');
    if (!$this->token) $this->getToken();
  }

  public function isChannelRunning($chid) {
    return $this->ota_running($this->lcode, $chid);
  }

  public function getOTAs($lcode) {
    return $this->get_otas($lcode);
  }

  public function getToken() {
    $this->token = $this->server->acquire_token($this->username, $this->password, $this->provider_key)[1];
    Redis::set('wubook_cm:token', $this->token);
  }

  public function releaseToken() {
    $this->server->release_token();
  }

  public function getRooms($lcode) {
    return $this->fetch_rooms($lcode);
  }

  public function newHotel($lodg, $acode) {
    return $this->corporate_new_property($lodg, 0, $acode);
  }

  public function newRoom($room) {
    $rid = $this->new_room($room['lcode'], $room['woodoo'], $room['name'], $room['beds'], $room['defprice'], $room['avail'], $room['shortname'], $room['defboard']);

    return [$rid, $room['shortname']];
  }

  public function createBookingConnection($lcode) {
    return $this->new_ota($lcode, self::BOOKING);
  }

  public function deleteRoom($lcode, $rcode) {
    return $this->del_room($lcode, $rcode);
  }

  public function setAvailability(int $lcode, $startDate, array $rooms) {
    $this->update_avail($lcode, fdate($startDate), $rooms);

    return true;
  }

  public function setPrices($pid, $lcode, $startDate, $prices) {
    $this->update_plan_prices($lcode, $pid, fdate($startDate), $prices);

    return true;
  }

  public function setRestrictions($pid, $lcode, $startDate, $rules) {
    $this->rplan_update_rplan_values($lcode, $pid, fdate($startDate), $rules);

    return true;
  }

  public function getPricingPlans($lcode) {
    return $this->server->get_pricing_plans($lcode);
  }

  public function getNewReservations($lcode) {
    return $this->fetch_new_bookings($lcode);
  }

  public function getReservation($lcode, $rcode) {
    return $this->fetch_booking($lcode, $rcode, true);
  }

  public function activatePush($hotelID) {
    return $this->push_activation($hotelID, $this->pushURL);
  }

  public function getRatePlans() {
    return $this->rplan_rplans($this->lcode);
  }

  public function missReservation($lcode, $rcode) {
    return $this->bcom_notify_noshow($lcode, $rcode);
  }

  public function __call($name, $args) {
    $targs = array_merge([$this->token], $args);

    $response = $this->server->__call($name, $targs);

    if (!isset($response[0])) {
      throw new \Exception(json_encode($response));
    }

    if (isset($response[0]) && $response[0] === -1 && $this->fails < 5) {
      $this->fails ++;
      $this->getToken();
      return $this->__call($name, $args);
    } else if ($this->fails >= 5) {
      throw new \Exception("Auth failed");
    }

    if (isset($response[0]) && $response[0] !== 0) {
      throw new Exception($response[1]);
    }

    $this->fails = 0;

    return $response[1];
  }
}