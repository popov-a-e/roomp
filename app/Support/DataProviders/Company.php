<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 02.04.2018
 * Time: 17:16
 */

namespace Roomp\Drivers\DataProviders;


use GuzzleHttp\Client;

class Company {
  private $client, $token;

  public function __construct(Client $client) {
    $this->client = $client;
    $this->token = 'a9c179eea4235da8faec4d2e1ea2e750b89a1df0';
  }

  public function getByINN($INN) {
    $response = $this->client->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/party', [
      'headers' => ['Accept' => 'application/json', 'Content-Type' => 'application/json', 'Authorization' => "Token {$this->token}"],
      'json' => ['query' => $INN]
    ]);

    return json_decode($response->getBody()->getContents())->suggestions[0]->data ?? null;
  }
}