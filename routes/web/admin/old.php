<?php

$admin = function () {
  Route::group(['middleware' => 'Roomp\Http\Middleware\RedirectIfNotAdmin'], function () {
    Route::get('/', 'Admin\HomeController@index');

    Route::get('/meta', 'Admin\HomeController@getMeta');

    Route::get('/reports/{id}', 'Storage\ImageController@getReport');
    Route::get('/scan/{id}', 'Storage\DocumentController@getScan');
    Route::post('/scan', 'Storage\DocumentController@uploadScan');

    Route::get('/reconcilliation', 'Admin\ReservationController@reconcilliation');
    Route::get('/reconcilliation/report', 'Admin\ReservationController@reconcilliationReport');
    Route::get('/reconcilliation/doc/{year}/{month}/{hotel_id}/{type}', 'Admin\ReservationController@reconcilliationDocument');
    Route::get('/reconcilliation/generate_doc', 'Admin\ReservationController@generateHotelReport');

    Route::get('/reconcilliation/generate', 'Admin\ReservationController@generateDocs');
    Route::get('/reconcilliation/send_email', 'Admin\ReservationController@sendEmail');


    Route::get('/occupancies/{id}', 'Admin\ReservationController@getOccupancies');
    Route::post('/occupancy', 'Admin\ReservationController@setOccupancy');

    Route::group(['prefix' => 'reservations'], function() {
      Route::get('', 'Admin\ReservationController@getAll');
      Route::post('', 'Admin\ReservationController@edit');
      Route::any('report', 'Admin\ReservationController@generateReport');
      Route::get('hotels', 'Admin\ReservationController@getHotels');
      Route::get('rooms', 'Admin\ReservationController@getRooms');
      Route::post('create', 'Admin\ReservationController@create');
    });

    Route::group(['prefix' => '/reservation/{code}'], function() {
      Route::get('', 'Admin\ReservationController@getOne');
      Route::delete('', 'Admin\ReservationController@delete');
      Route::post('add_comment', 'Admin\ReservationController@addComment');
      Route::get('miss', 'Admin\ReservationController@miss');
      Route::get('cancel', 'Admin\ReservationController@cancel');
      Route::get('overbooking', 'Admin\ReservationController@overbooking');
      Route::get('resend_email', 'Admin\ReservationController@resendEmail');
      Route::get('resend_email_to_hotelier', 'Admin\ReservationController@resendEmailToHotelier');
      Route::get('get_ostrovok_phone', 'Admin\ReservationController@getOstrovokPhone');
      Route::get('push_status', 'Admin\ReservationController@pushStatus');
      Route::get('channel_push', 'Admin\ReservationController@channelPush');
    });

    Route::get('/hotels', 'Admin\HotelController@getAll');

    Route::get('/geoapi', function(\Roomp\Drivers\Yandex\GeocodeAPI $API, \Illuminate\Http\Request $request) {
      return $API->getLatLngByAddress($request->input('address'));
    });

    Route::post('/hotel/new', 'Admin\HotelController@create');

    Route::group(['prefix' => '/hotel/{id}'], function() {
      Route::get('', 'Admin\HotelController@getOne');
      Route::post('', 'Admin\HotelController@change');
      Route::delete('','Admin\HotelController@del');

      Route::get('policy', 'Admin\HotelController@getPolicy');
      Route::get('availability', 'Admin\HotelController@getAvailability');
      Route::get('prices', 'Admin\HotelController@getPrices');
      Route::get('prices/rates', 'Admin\HotelController@getPriceRates');
      Route::get('rooms', 'Admin\HotelController@getRooms');

      Route::get('/logs', 'Admin\HotelController@getLogs');
      Route::post('/logs', 'Admin\HotelController@log');

      Route::get('organization', 'Admin\HotelController@getOrganization');
      Route::post('organization', 'Admin\HotelController@setOrganization');

      Route::post('locale', 'Admin\HotelController@setLocales');


      Route::group(['prefix' => 'offer'], function() {
        Route::get('', 'Admin\OfferController@get');
        Route::get('send_email', 'Admin\OfferController@sendEmail');
        Route::get('send_instructions_email', 'Admin\OfferController@sendInstructionsEmail');
        Route::get('static', 'Admin\OfferController@getStaticFile');
        Route::get('create', 'Admin\OfferController@create');
        Route::get('terminate', 'Admin\OfferController@terminate');
        Route::get('generate', 'Admin\OfferController@generate');
        Route::get('regenerate', 'Admin\OfferController@regenerate');
        Route::get('reset_password', 'Admin\OfferController@resetPassword');
      });

      Route::post('update_goody_bags', 'Admin\HotelController@updateGoodyBags');
      Route::post('comment', 'Admin\HotelController@setComment');
      Route::post('contacts', 'Admin\HotelController@updateContact');
      Route::delete('contacts', 'Admin\HotelController@deleteContact');

      Route::get('info', 'Admin\HotelController@getOneInfo');

      Route::get('booking_data', 'Admin\HotelController@getBookingData');
    });

    Route::prefix('business_developers')->group(function() {
      Route::get('', 'Admin\BDController@all');
      Route::get('{id}', 'Admin\BDController@find');

      Route::post('', 'Admin\BDController@update');
    });

    Route::get('/rooms/{id}', 'Admin\HotelController@getRooms');

    Route::get('/hoteliers', 'Admin\HotelController@getHoteliers');
    Route::get('/organizations', 'Admin\HotelController@getOrganizations');
    Route::get('/organization/{id}', 'Admin\HotelController@getOrganizationByID');
    Route::put('/room', 'Admin\RoomController@create');

    Route::group(['prefix' => '/room/{id}'], function() {
      Route::get('', 'Admin\RoomController@getOne');
      Route::post('', 'Admin\RoomController@change');
      Route::delete('', 'Admin\RoomController@delete');
    });

    Route::group(['prefix' => '/rates/{id}'], function () {
      Route::get('', 'Admin\RatesController@get');
      Route::post('', 'Admin\RatesController@update');

      Route::post('dynamic', 'Admin\RatesController@updateDynamic');
      Route::post('policy', 'Admin\RatesController@updateBookingPolicy');
    });

    Route::group(['prefix' => 'channels'], function () {
      Route::get('/sync/availability/{id}', 'Admin\ChannelController@syncAvailability');
      Route::get('/sync/prices/{id}', 'Admin\ChannelController@syncPrices');
      Route::get('/tie/{id}/{margin}', 'Admin\ChannelController@tiePrices');
      Route::get('/close/{id}', 'Admin\ChannelController@closeAvailability');

      Route::group(['prefix' => 'ostrovok'], function () {
        Route::get('hotels', '\Roomp\Drivers\OTA\Ostrovok\ConnectionController@getHotels');
        Route::post('set_rooms', '\Roomp\Drivers\OTA\Ostrovok\ConnectionController@set');
        Route::get('refresh', '\Roomp\Drivers\OTA\Ostrovok\ConnectionController@refresh');

        Route::post('{hotelID}', '\Roomp\Drivers\OTA\Ostrovok\ConnectionController@save');
        Route::get('{hotelID}', '\Roomp\Drivers\OTA\Ostrovok\ConnectionController@get');
      });

      Route::group(['prefix' => 'booking'], function () {
        Route::get('rates/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@rates');
        Route::get('rooms/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@rooms');
        Route::get('rooms/{hotelID}/sync', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@syncRooms');

        Route::post('premium_program/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@setPremiumProgram');

        Route::get('disable/{hotelID}', 'Roomp\Drivers\OTA\Wubook\BookingConnectionController@closeConnection');
        Route::get('create/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@createNewOTA');
        Route::get('start/{hotelID}/{bookingID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@startProcedure');
        Route::get('confirm/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@confirmActivation');
        Route::get('init/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@initChannel');
        Route::get('get/{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@getRoomsRates');
        Route::post('set_rates', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@setRateMapping');
        Route::post('set_rooms', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@setRoomMapping');

        Route::get('{hotelID}', '\Roomp\Drivers\OTA\Wubook\BookingConnectionController@get');
      });
    });

    Route::get('/api_channels', 'Admin\HotelController@getChannels');
    /*
        Route::get('/bcom', function(\Illuminate\Http\Request $request, \GuzzleHttp\Client $client) {
          $json = json_decode($request->input('json'));

          $cookies = [];

          dd (urldecode($json->cookie));

          foreach (explode(';', urldecode($json->cookie)) as $str) {
            $vals = explode('=', $str);
            $cookies[trim($vals[0])] = $vals[1];
          }

          $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray($cookies, 'booking.com');

          dd ($cookies);

          dd ($client->get('https://admin.booking.com/hotel/hoteladmin/extranet_ng/manage/json/ranking_onsite_aggregate.json',[
            'cookies' => $cookieJar,
            'query' => [
              'hotel_id' => 2553552,
              'ses' => $json->ses,
              'lang' => 'ru'
            ]
          ])->getBody()->getContents());
        });*/

    Route::group(['prefix' => 'policies'], function () {
      Route::get('{id}', 'Admin\PoliciesController@get');
      Route::post('{id}', 'Admin\PoliciesController@set');
    });

    Route::group(['prefix' => 'promo_codes'], function() {
      Route::get('', 'Admin\PromoCodeController@all');
      Route::post('', 'Admin\PromoCodeController@set');
      Route::post('force', 'Admin\PromoCodeController@setForce');

      Route::group(['prefix' => '{id}'], function() {
        Route::get('', 'Admin\PromoCodeController@get');

        Route::put('activate', 'Admin\PromoCodeController@activate');
        Route::put('deactivate', 'Admin\PromoCodeController@deactivate');
        Route::delete('', 'Admin\PromoCodeController@delete');
      });
    });

    Route::get('/list/{source}', 'Admin\HomeController@getList');

    Route::post('/photo', 'Storage\ImageController@upload');
    Route::post('/photo/map', 'Storage\ImageController@uploadMap');

    Route::get('/router_meta', 'Admin\MetaController@getMeta');

    Route::get('/logout', 'Admin\Auth\LoginController@logout');
  });

  Route::group(['middleware' => 'Roomp\Http\Middleware\RedirectIfAdmin'], function () {
    Route::get('/login', function () {
      return view('admin.login.index', ['translations' => ['dates' => \Illuminate\Support\Facades\Lang::get('dates')]]);
    });

    Route::post('/login', 'Admin\Auth\LoginController@login');
  });
};