<?php

function abort_wubook($code, $message, $error = true) {

  abort(200,htmlspecialchars_decode(json_encode((object)[
    'code' => $code,
    ($error ? 'error' : 'message') => $message
  ],JSON_UNESCAPED_UNICODE)),['Content-Type' => 'application/json; charset: utf-8']);
}

function pluralize ($tr, $n) {
  $arr = __($tr);

  $getStr = function($n) use ($arr) {
    if ($n === 1 && isset($arr[3])) return $arr[3];

    switch (true) {
      case $n > 10 && $n < 20:
        return $arr[2];
      case $n % 10 === 1:
        return $arr[0];
      case $n % 10 === 2:
      case $n % 10 === 3:
      case $n % 10 === 4:
        return $arr[1];
      default:
        return $arr[2];
    }
  };

  $str = $getStr($n);

  if (strrpos($str,'*') !== false) return str_replace('*',$n,$str);

  return $n . ' ' . $str;
};

if (!function_exists('carbon')) {
  function carbon(string $dateString) {
    return \Carbon\Carbon::parse($dateString);
  }
}

function toCurrency($number, $currency = null) {
  $currency = $currency ?? session('currency') ?? 'RUB';
  $currencyStr = app('currency_provider')[$currency];

  return str_replace(':n', $number, $currencyStr);
}

function ceil_to_cents($number) {
  return ceil(floor($number * 10000) / 100) / 100;
}

if (!function_exists('image_url')) {
  function image_url($filename, $quality = 1000) {
    return \Illuminate\Support\Facades\Storage::url("hotels/photos/{$quality}p/".$filename);
  }
}

if (!function_exists('cloud_storage_root')) {
  function cloud_storage_root() {
    return preg_replace('|//$|im', '/', \Illuminate\Support\Facades\Storage::url('/'));
  }
}

if (!function_exists('cdn')) {
  function cdn($url) {
    $cdn_path = env('CDN_URL') ?? '';

    try {
      $url = mix($url);
    } catch (\Exception $e) {
      if ($e->getCode() !== 100) throw $e;
      if ($url[0] !== '/') $url = '/' . $url;
    }
    return $cdn_path . $url;
  }
}

if (!function_exists('map_image_url')) {
  function map_image_url($filename) {
    return \Illuminate\Support\Facades\Storage::url("hotels/maps/".$filename);
  }
}

function sum_to_russian_string($num) {
  $morph = function($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
  };

  $nul='ноль';
  $ten=array(
    array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
    array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
  );
  $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
  $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
  $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
  $unit=array( // Units
    array('копейка' ,'копейки' ,'копеек',	 1),
    array('рубль'   ,'рубля'   ,'рублей'    ,0),
    array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
    array('миллион' ,'миллиона','миллионов' ,0),
    array('миллиард','милиарда','миллиардов',0),
  );
  //
  list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
  $out = array();
  if (intval($rub)>0) {
    foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
      if (!intval($v)) continue;
      $uk = sizeof($unit)-$uk-1; // unit key
      $gender = $unit[$uk][3];
      list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
      // mega-logic
      $out[] = $hundred[$i1]; # 1xx-9xx
      if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
      else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
      // units without rub & kop
      if ($uk>1) $out[]= $morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
    } //foreach
  }
  else $out[] = $nul;
  $out[] = $morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
  $out[] = $kop.' '.$morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
  return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}