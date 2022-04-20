<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(\GuzzleHttp\Client $client) {
  $r = $client->get('https://www.booking.com', [
    'headers' => [
      'Host' => 'www.booking.com',
      'Connection' => 'keep-alive',
      'Pragma' => 'no-cache',
      'Cache-Control' => 'no-cache',
      'Upgrade-Insecure-Requests' => 1,
      'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36',
      'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
      'Accept-Encoding' => 'utf-8',
      'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
      'Cookie' => 'cors_js=1; BJS=-; _ga=GA1.2.332753727.1535980567; _gid=GA1.2.91364135.1535980567; header_signin_prompt=1; zz_cook_tms_seg1=1; zz_cook_tms_ed=1; zz_cook_tms_seg3=5; cto_lwid=4f50fdb5-6e5d-4cd8-8ad4-7014b5766ec7; _ym_uid=1535980567267262222; _ym_d=1535980567; cws=true; lang_signup_prompt=1; has_preloaded=1; zz_cook_tms_hlist=621995; vpmss=1; ecid=3B0ekUk36BGNvtfpBB39ZwO%2F; esadm=02UmFuZG9tSVYkc2RlIyh9YbxZGyl9Y5%2BP3XqzXEKn7ensgKR6tTUAV18s%2FvHT5ehMkR0f7CHUfQg%3D; ux=e; _ym_isad=1; lastSeen=0; utag_main=v_id:01659f937a680018b927206c6af403073002506b00bd0$_sn:5$_ss:1$_st:1536077181030$4split:1$4split2:2$ses_id:1536075381030%3Bexp-session$_pn:1%3Bexp-session; _tq_id.TV-81277227-1.3b4c=8539e05cfeeee2c0.1535980567.0.1536075382..; bkng=11UmFuZG9tSVYkc2RlIyh9Yaa29%2F3xUOLbVA9iGwA%2BUSxe0Ni4L%2FEhaA8V6pfJ%2FvpEqKocDXIU62sGZCP79qS02EWcmztlHh3n6esSXCF3KR4RgInTPQmHaU4DkcPjmJOIAffm8sqmc0ix7okW1hdg2JMcQZcVm1ltx%2BPHt5Ta6i8XSBNkM1RMZwc1ez6IXNjqOM5azgqkAoo%3D',
    ]
  ]);

  return $r->getBody()->getContents();
});

Route::prefix('r')->group(function () {
  Route::get('cancel', 'Reservation@cancel');
  Route::get('{code}', 'Reservation@retrieve');
});

Route::get('search', 'Search@index');
Route::post('search', 'Search@show');

Route::get('/hotel/{pretty_url}', 'HotelController@index');
Route::get('/hotel_rooms', 'HotelController@rooms');
