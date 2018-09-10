<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 13.04.2017
 * Time: 20:02
 */

namespace Roomp\Drivers\SMS;

use GuzzleHttp\Client;

class SMS {
  private $login;
  private $password;
  private $charset;
  private $fmt;

  private $client;

  public function __construct($config, Client $client) {
    $this->login = $config['login'];
    $this->password = $config['password'];
    $this->fmt = $config['fmt'];
    $this->charset = $config['charset'];

    $this->client = $client;
  }

  public function send($to, $message){
    return $this->client->get('https://smsc.ru/sys/send.php',[
      'query' => [
        'login' => $this->login,
        'psw' => $this->password,
        'phones' => $to,
        'mes' => $message,
        'charset' => $this->charset
      ]
    ])->getBody();
  }
}