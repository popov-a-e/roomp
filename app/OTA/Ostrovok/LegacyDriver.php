<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 16.08.2017
 * Time: 11:27
 */

namespace Roomp\OTA\Ostrovok;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class LegacyDriver {
  private $client;
  private $redisKey;
  private $cookie;
  private $hotelID;
  private $repository;

  public function __construct(Client $client, Repository $repository) {
    $this->client = $client;
    $this->redisKey = 'ostrovok:cookie';
    $this->repository = $repository;

    $this->cookie = Redis::get($this->redisKey);
  }

  public function getPhone($reservation) {
    $ostrovokReservation = $this->repository->getOstrovokReservation($reservation->id);

    if (!$ostrovokReservation) return 'Нет данных';

    $response = json_decode($this->getBookings($ostrovokReservation->ostrovok_hotel_id, $reservation->from, $reservation->from));

    $bookings = array_map(function($b) {return $b->bookings[0];}, $response->objects);

    return collect($bookings)->where('id', $ostrovokReservation->ostrovok_id)->first()->phone;
  }

  public function getBookings($hotelIID, $from, $till) {
    $this->hotelID = $hotelIID;

    $data = [
      'query' =>
        [
          'hotel' => $this->hotelID,
          'filter' => 'arrive_at',
          'start_at' => $from,
          'end_at' => $till,
          'limit' => 25,
          'offset' => 0,
          'lang' => 'ru'
        ]
    ];

    return $this->request('GET', 'https://extranet.ostrovok.ru/api/internal/v3/order/', $data);
  }

  public function missReservation($reservation) {
    $this->hotelID = $reservation->ostrovok_hotel_id;

    $reservationID = $reservation->ostrovok_id;

    $url = "https://extranet.ostrovok.ru/api/internal/v3/booking/$reservationID/";

    $data = [
      'json' => [
        'status' => 'missed',
        'reason' => null
      ]
    ];

    return $this->request('PUT', $url, $data);
  }

  public function refreshCookie() {
    $url = "https://extranet.ostrovok.ru/api/internal/v3/user/";

    $cookie = $this->client->request('POST', $url, [
      'form_params' => [
        'username' => 'admin@roomp.co',
        'password' => 'ne23fa34'
      ]
    ])->getHeaders()['Set-Cookie'];

    Redis::set($this->redisKey, json_encode($cookie));

    $this->client->request('GET', 'https://extranet.ostrovok.ru/', [
      'cookie' => $cookie
    ])->getBody()->getContents();

    $this->cookie = $cookie;
  }

  public function request($method, $url, $data) {
    try {
      return $this->sendRequest($method, $url, $data);
    } catch (\Exception $exception) {
      $this->refreshCookie();
      return $this->sendRequest($method, $url, $data);
    }
  }

  public function sendRequest($method, $url, $data) {
    $toSend = [
      'headers' => [
        'Accept' => '*/*',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
        'Cache-Control' => 'no-cache',
        //'Connection' => 'keep-alive',
        'Content-Length' => 33,//strlen(json_encode($data)),
        'Content-Type' => 'application/json',
        'Cookie' => $this->cookie,
        'Host' => 'extranet.ostrovok.ru',
        'Origin' => 'https://extranet.ostrovok.ru',
        'Pragma' => 'no-cache',
        'Referer' => 'https://extranet.ostrovok.ru/',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
        'X-Requested-With' => 'XMLHttpRequest',
        'X-Compress' => null,
      ]
    ];

    $toSend = array_merge($data, $toSend);

    return $this->client->request($method, $url, $toSend)->getBody()->getContents();
  }
}