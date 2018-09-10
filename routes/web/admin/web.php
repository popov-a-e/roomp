<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:admins')->group(function () {
  Route::get('', 'HomeController@index')->middleware('share.user:admins');

  Route::namespace('Hotels')->group(require 'hotels.php');
  Route::name('reservations.')->group(require 'reservations.php');

  Route::apiResource('hoteliers', 'HotelierController');
  Route::prefix('channels')->namespace('Channels')->group(function() {
    Route::prefix('ostrovok')->group(function() {
      Route::get('', 'Ostrovok@index');
      Route::get('refresh', 'Ostrovok@refresh');
    });
  });

  Route::prefix('organizations')->group(function () {
    Route::get('', 'OrganizationController@index');
    Route::get('{organization}', 'OrganizationController@show');
  });

  Route::prefix('upload')->namespace('Storage')->group(function () {
    Route::post('photo', 'ImageController@uploadPhoto');
    Route::post('map', 'ImageController@uploadMap');
  });

  //@!
  Route::get('meta', 'HomeController@getMeta');
  Route::get('router_meta', 'MetaController@getMeta');

  Route::get('geoapi', '\Roomp\Drivers\Yandex\GeocodeAPI\GeocodeAPI@get');

  Route::get('logout', 'Auth\LoginController@logout');
});

Route::middleware('guest:admins')->group(function () {
  Route::get('login', 'Auth\LoginController@index');
  Route::post('login', 'Auth\LoginController@login');
});

Route::options('/bcom_data', '\Roomp\Drivers\BCom\Controller@options');
Route::post('/bcom_data', '\Roomp\Drivers\BCom\Controller@fill');
Route::get('/bcom_data_xls', '\Roomp\Drivers\BCom\Controller@getFile');
/*
  Route::group(['middleware' => 'Roomp\Http\Middleware\RedirectIfNotAdmin', 'namespace' => 'Admin'], function () {
    Route::prefix('business_developers')->group(function() {
      Route::get('', 'BDController@all');
      Route::get('{id}', 'BDController@find');

      Route::post('', 'BDController@update');
    });

    Route::get('/api_channels', 'HotelController@getChannels');

    Route::group(['prefix' => 'promo_codes'], function() {
      Route::get('', 'PromoCodeController@all');
      Route::post('', 'PromoCodeController@set');
      Route::post('force', 'PromoCodeController@setForce');

      Route::group(['prefix' => '{id}'], function() {
        Route::get('', 'PromoCodeController@get');

        Route::put('activate', 'PromoCodeController@activate');
        Route::put('deactivate', 'PromoCodeController@deactivate');
        Route::delete('', 'PromoCodeController@delete');
      });
    });

    Route::prefix('reconciliation')->group(function() {
      Route::get('', 'ReservationController@reconcilliation');
      Route::get('report', 'ReservationController@reconcilliationReport');
      Route::get('doc/{year}/{month}/{hotel_id}/{type}', 'ReservationController@reconcilliationDocument');
      Route::get('generate_doc', 'ReservationController@generateHotelReport');
      Route::get('generate', 'ReservationController@generateDocs');
      Route::get('send_email', 'ReservationController@sendEmail');
    });

    Route::get('/occupancies/{id}', 'ReservationController@getOccupancies');
    Route::post('/occupancy', 'ReservationController@setOccupancy');



    Route::get('/list/{source}', 'HomeController@getList');

  });*/