<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 20.11.2017
 * Time: 20:05
 */

namespace Roomp\Drivers\Analytics;


use TheIconic\Tracking\GoogleAnalytics\Analytics;

class Ecommerce {
  private $analytics;

  public function __construct(Analytics $analytics) {
    $this->analytics = $analytics;
  }

  public function init($clientID) {
    $this->analytics
      ->setProtocolVersion('1')
      ->setTrackingId('UA-84660025-' . (env('APP_IS_BETA') ? 3 : 1))
      ->setClientId($clientID);
  }

  public function processReservation($reservation) {
    if ($reservation->status->status->active === false) {
      $this->sendReturn($reservation);
    } else {
      $this->sendTransaction($reservation);
    }
  }

  public function sendTransaction($reservation) {
    $client = $reservation->creator;

    if (!$client || !$client->google_client_id) throw new \Exception("No client ID");

    $this->init($client->google_client_id);

    $this->analytics->setTransactionId($reservation->code)
      ->setAffiliation($reservation->hotel->ru)
      ->setRevenue($reservation->total)
      ->setTax($reservation->rate);

    if ($reservation->promoCode) {
      $this->analytics->setCouponCode($reservation->promoCode->code);
    }

    $reservation->occupancies->each(function($occupancy) use ($reservation) {
      $room = $occupancy->room;
      $allotment = $occupancy->allotment;

      $this->analytics->addProduct([
        'name' => $room->name,
        'brand' => $room->hotel->ru,
        'category' => $room->class->ru,
        'variant' => $allotment->ru,
        'price' => $occupancy->price,
        'quantity' => $reservation->nights,
      ]);
    });

    $this->analytics->setProductActionToPurchase();

    $this->analytics->setEventCategory('Ecommerce')
      ->setEventAction('Purchase')
      //->setNonInteractionHit('1')
      ->sendEvent();
  }

  public function sendReturn($reservation) {
    $client = $reservation->creator;

    if (!$client || !$client->google_client_id) throw new \Exception("No client ID");

    $this->init($client->google_client_id);

    $this->analytics->setTransactionId($reservation->code);

    $this->analytics->setProductActionToRefund();

    $this->analytics->setEventCategory('Ecommerce')
      ->setEventAction('Refund')
      //->setNonInteractionHit('1')
      ->sendEvent();
  }
}