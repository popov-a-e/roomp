<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 10.03.2017
 * Time: 12:27
 */

namespace Roomp\OTA\Ostrovok;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Driver {
  private $authToken;
  private $privateToken;

  private $version;
  private $endpoint;

  private $client;
  private $limits = false;

  public function __construct(Client $client) {
    $this->authToken = config('ota.ostrovok.auth_token');
    $this->privateToken = config('ota.ostrovok.private_token');
    $this->version = config('ota.ostrovok.version');
    $this->endpoint = config('ota.ostrovok.endpoint');

    $this->client = $client;
  }


  public function getHotels() {
    $response = $this->request('hotels');

    $this->limits = 1000;

    return $this->response('hotels', $response);
  }

  public function getRooms($hotel = null) {
    $response = $this->request('room_categories', compact('hotel'));

    $this->limits = 1000;

    return $this->response('room_categories', $response);
  }

  public function getOccupancies($hotel = null) {
    $response = $this->request('occupancies', compact('hotel'));

    $this->limits = 1000;

    return $this->response('occupancies', $response);
  }

  public function getRatePlans($hotel = null) {
    $response = $this->request('rate_plans', compact('hotel'));

    $this->limits = 1000;

    return $this->response('rate_plans', $response);
  }

  public function getReservations($hotelID = null, Carbon $from = null, Carbon $till = null) {
    $this->limits = 1000;

    $data = [
      'hotel' => $hotelID,
    ];

    if ($from) $data['created_at_start_at'] = $from->toDateString();
    if ($till) $data['created_at_end_at'] = $till->toDateString();

    $request = $this->request('bookings', $data);

    return $this->response('bookings', $request);
  }

  public function setPrices(int $hotelID, array $occupancies) {
    $data = [
      'hotel' => $hotelID,
      'occupancies' => $occupancies
    ];

    return $this->request('rna', $data, 'POST');
  }

  public function getPrices($hotelID, Carbon $from = null, Carbon $till = null) {
    $data = [
      'hotel' => $hotelID
    ];

    if ($from) $data['plan_date_start_at'] = $from->toDateString();
    if ($till) $data['plan_date_end_at'] = $till->toDateString();

    $this->limits = 1000;

    $request = $this->request('rna', $data, 'GET');

    return $this->response('occupancies', $request);
  }

  public function setAvailability($hotelID, $room_categories) {
    $data = [
      'hotel' => $hotelID,
      'room_categories' => $room_categories
    ];

    $request = $this->request(' rna', $data, 'POST');


    return $this->response('room_categories', $request);
  }

  public function getAvailability($hotelID, Carbon $from = null, Carbon $till = null) {
    $data = [
      'hotel' => $hotelID
    ];

    $this->limits = 1000;

    if ($from) $data['plan_date_start_at'] = $from->toDateString();
    if ($till) $data['plan_date_end_at'] = $till->toDateString();

    $request = $this->request('rna', $data, 'GET');

    return $this->response('room_categories', $request);
  }

  public function setRestrictions($hotelID, $rate_plans) {
    $data = [
      'hotel' => $hotelID,
      'rate_plans' => $rate_plans
    ];

    $request = $this->request('rna', $data, 'POST');


    return $this->response('rate_plans', $request);
  }

  private function url(string $action) : string {
    return $this->endpoint . '/' . $this->version . '/' . $action . '/';
  }

  private function request(string $action, array $params = [], $method = 'GET') {
    $url = $this->url($action);

    $filter = $this->getFilters($params);

    return $this->send($url, $filter, $method);
  }

  private function send($url, array $data = [], $method = 'GET') {
    if ($this->limits) $data['limits'] = $this->limits;
    $query = $this->sign($data);

    $response = $this->client->request($method, $url, [
      'json' => $data,
      'query' => $query
    ]);

    return $response->getBody()->getContents();
  }

  private function response(string $name, $response) {
    $json = json_decode($response);

    return $json->$name;
  }


  private function getFilters(array $filters) : array {
    return array_filter($filters, function ($elem) {
      return $elem;
    });
  }

  private function sign(array $data) : array {
    $sign = $this->getSignature($data);

    $query = [
      'token' => $this->authToken,
      'sign' => $sign
    ];

    if ($this->limits) $query['limits'] = $this->limits;

    foreach (['hotel', 'limits', 'page', 'created_at_end_at', 'created_at_start_at'] as $field) {
      if (isset($data[$field])) {
        $query[$field] = $data[$field];
      }
    }

    ksort($query);

    return $query;
  }

  private function getSignature(array $data) : string {
    $data['private'] = $this->privateToken;
    $data['token'] = $this->authToken;

    $dataString = $this->arrayToString($data);

    return md5($dataString);
  }

  private function arrayToString(array $data) : string {
    ksort($data);

    return collect($data)->map(function ($value, $key) {
      if (is_array($value)) {
        $arr = array_map(function ($elem) {
          return $this->arrayToString($elem);
        }, $value);

        if (count($arr) > 1) {
          $val = '[' . implode(';', $arr) . ']';
        } else {
          $val = $arr[0];
        }
      } else {
        $val = $value;
      }

      if (is_bool($val)) {
        return "$key=".($val ? 'true' : 'false');
      }

      return "$key=$val";
    })->implode(';');
  }
}