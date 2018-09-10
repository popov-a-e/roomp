<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 23.04.2017
 * Time: 2:52
 */

namespace Roomp\Drivers\Tinkoff;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;

class Tinkoff {
  private $key, $url, $password, $client, $repository;

  public function __construct($config, Client $client, Repository $repository) {
    $this->key = $config['key'];
    $this->url = $config['url'];
    $this->password = $config['password'];
    $this->client = $client;
    $this->repository = $repository;
  }

  public function init($reservation, $IP = null) {
    $response = $this->command("Init", [
      'TerminalKey' => $this->key,
      'OrderId' => $reservation->id,
      'Amount' => (int)ceil($reservation->total * 100),
      //'IP' => $IP,
      'Description' => __("reservation.reservation").' '.$reservation->code,
      'Language' => App::getLocale(),
    ]);

    $this->pushStatus($response);

    return $response;
  }

  public function refund($reservation) {
    $response = $this->command("Cancel", [
      'TerminalKey' => $this->key,
      'PaymentId' => $reservation->payment->payment_id,
      'Token' => hash('sha256', $this->password.$reservation->payment->payment_id.$this->key)
    ]);

    if ($response->Success) {
      $this->pushStatus($response);
      return true;
    }

    return false;
  }

  public function pushStatus($responseData) {
    return $this->repository->pushStatus($responseData->Status, $responseData->OrderId, $responseData->Amount ?? $responseData->NewAmount, $responseData->PaymentId);
  }

  public function command($cmd, $json) {
    $request = $this->client->request('POST', $this->url . $cmd, compact('json'));

    return json_decode($request->getBody()->getContents());
  }
}