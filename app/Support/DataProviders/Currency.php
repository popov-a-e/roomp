<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 09.04.2018
 * Time: 12:28
 */

namespace Roomp\Drivers\DataProviders;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Redis;

class Currency {
  private $client, $key;

  public function __construct(Client $client) {
    $this->client = $client;
    //$this->key = '545e1d1e665be6083371aa63d83a0212';
    $this->key = 'XxSbWB4ybEdTt58cpX8YkMVYWXvMW9';
  }

  public function getCurrencyRatio($from, $to) {
    $directWay = "{$to}_{$from}";
    $reverseWay = "{$from}_{$to}";

    $response = $this->client->get('https://free.currencyconverterapi.com/api/v5/convert', [
      'query' => [
        'q' => "$directWay,$reverseWay",
      ]
    ]);

    $data = json_decode($response->getBody()->getContents());

    return [
      $data->results->{$directWay}->val,
      $data->results->{$reverseWay}->val
    ];
  }

  public function getCachedCurrencyRatio($from, $to) {
    $ratio = Redis::get("{$from}:{$to}");

    if (!$ratio) {
      $ratio = $this->getCurrencyRatio($from, $to)[1];
      Redis::set("{$from}:{$to}", $ratio);
    }

    return $ratio;
  }
}