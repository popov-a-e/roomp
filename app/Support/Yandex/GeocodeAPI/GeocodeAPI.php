<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 30.03.2018
 * Time: 17:57
 */

namespace Roomp\Drivers\Yandex\GeocodeAPI;


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Roomp\Http\Controllers\Controller;

class GeocodeAPI extends Controller {
  private $client;

  public function __construct(Client $client) {
    $this->client = $client;
  }

  public function get(Request $request) {
    return $this->getLatLngByAddress($request->address);
  }

  private function getLatLngByAddress(string $address) {
    $response = $this->client->get('https://geocode-maps.yandex.ru/1.x', [
      'query' => [
        'geocode' => $address,
        'format' => 'json'
      ]
    ]);

    $latlng = explode(' ', json_decode($response->getBody()->getContents())->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos ?? '');

    $lat = $latlng[1] ?? 0;
    $lng = $latlng[0] ?? 0;

    return [$lat, $lng];
  }
}