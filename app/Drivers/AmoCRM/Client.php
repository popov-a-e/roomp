<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 24.12.2017
 * Time: 22:54
 */

namespace Roomp\Drivers\AmoCRM;


use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Redis;

class Client {
  private $login;
  private $hash;
  private $guzzle;
  private $authFails = 0;

  public function __construct(Guzzle $guzzle) {
    $this->login = config('amocrm.login');
    $this->hash = config('amocrm.hash');
    $this->guzzle = $guzzle;
    $this->cookie = Redis::get('amocrm:cookie');
  }

  public function getLeadData($id) {
    $data = $this->command('leads', 'GET', [
      'id' => $id
    ]);

    return $data->_embedded->items[0];
  }

  public function getContactData($id) {
    $data = $this->command('contacts', 'GET', [
      'id' => $id
    ]);

    return $data->_embedded->items[0];
  }

  public function getPipelineData($id) {
    $data = $this->command('pipelines');

    return $data->_embedded->items->$id;
  }

  public function moveLead($leadID, $leadName, $newStatus, $updatedAt) {
    return $this->command('leads', 'POST', [
      'update' => [[
        'id' => $leadID,
        'status_id' => $newStatus,
        'updated_at' => $updatedAt,
        'name' => $leadName
      ]]
    ]);
  }

  public function getCustomFields() {
    return $this->request('POST', 'https://roomp.amocrm.ru/ajax/settings/custom_fields/', [
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest'
      ]
    ])->response->params->fields->leads;
  }

  public function updateCustomField($fieldData) {
    return $this->request('POST', 'https://roomp.amocrm.ru/ajax/settings/custom_fields/', [
      'headers' => [
        'X-Requested-With' => 'XMLHttpRequest'
      ],
      'form_params' => $fieldData
    ]);
  }

  private function obtainAuthCookie() {
    $response = $this->guzzle->post('https://roomp.amocrm.ru/private/api/auth.php', [
      'json' => [
        'USER_LOGIN' => $this->login,
        'USER_HASH' => $this->hash,
      ],
      'query' => ['type' => 'json']
    ]);

    $cookie = $response->getHeader('Set-Cookie');
    $cookie = implode(';', $cookie);

    Redis::set('amocrm:cookie', $cookie);
    $this->cookie = $cookie;
  }

  private function request($method, $uri, $params, $async = false) {
    $replace_unicode_escape_sequence = function($match) {
      return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
    };

    $unicode_decode = function($str) use ($replace_unicode_escape_sequence) {
      return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', $replace_unicode_escape_sequence, $str);
    };

    $params['headers'] = $params['headers'] ?? [];
    $params['headers']['Cookie'] = $this->cookie;

    $requestType = $async ? 'requestAsync' : 'request';

    try {
      $response = $this->guzzle->$requestType($method, $uri, $params);
    } catch (\Exception $e) {
      if ($this->authFails > 5) abort(400, $unicode_decode($e->getMessage()));
      $this->authFails++;
      $this->obtainAuthCookie();

      return $this->request($method, $uri, $params);
    }

    if ($async) return $response;

    return json_decode($response->getBody()->getContents());
  }

  public function command(string $type, $method = 'GET', array $query = []) {
    return $this->request($method, 'https://roomp.amocrm.ru/api/v2/' . $type, [
      ($method === 'GET' ? 'query' : 'json') => $query
    ]);
  }
}