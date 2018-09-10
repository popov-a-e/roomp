<?php

use Illuminate\Support\Facades\Route;

return function() {
  Route::apiResource('hotels', 'HotelController');

  Route::prefix('hotels')->group(function() {
    Route::prefix('{hotel}')->group(function () {
      Route::get('info', 'HotelController@showInfo');

      Route::post('update_goody_bags', 'HotelController@updateGoodyBags');
      Route::post('comment', 'HotelController@updateComment');

      Route::prefix('rates')->group(function () {
        Route::get('', 'RatesController@show');
        Route::put('', 'RatesController@update');
      });

      Route::prefix('policy')->group(function () {
        Route::get('', 'PolicyController@show');
        Route::put('', 'PolicyController@update');
      });

      Route::prefix('logs')->group(function () {
        Route::get('', 'LogController@show');
        Route::put('', 'LogController@store');
      });

      Route::prefix('organization')->group(function () {
        Route::get('', 'OrganizationController@show');
        Route::put('{organization}', 'OrganizationController@update');

        Route::put('{organization}/locales', 'OrganizationController@updateLocales');
      });

      Route::group(['prefix' => 'offer'], function () {
        Route::get('', 'OfferController@show');
        Route::get('file', 'OfferController@get');

        Route::get('register', 'OfferController@register');
        Route::get('reset_password', 'OfferController@resetPassword');

        Route::get('create', 'OfferController@create');
        Route::get('terminate', 'OfferController@terminate');

        Route::get('generate', 'OfferController@generate');
        Route::get('regenerate', 'OfferController@generate');
      });

      Route::get('availability', 'AvailabilityController@get');
      Route::get('prices', 'PricesController@get');
      Route::get('channel_prices', 'ChannelPricesController@get');
      Route::get('restrictions', 'RestrictionsController@get');

      Route::apiResource('contacts', 'ContactFaceController');
      Route::apiResource('rooms', 'RoomController');

      Route::prefix('channels')->namespace('Channels')->group(function () {
        Route::get('sync/availability}', 'SyncController@availability');
        Route::get('sync/prices', 'SyncController@prices');
        Route::get('sync/restrictions', 'SyncController@restrictions');
        Route::get('tie', 'ChannelController@tiePrices');
        Route::get('close', 'ChannelController@close');

        Route::prefix('ostrovok')->group(function() {
          Route::get('', 'Ostrovok@show');
          Route::post('','Ostrovok@mapRooms');
          Route::post('{ostrovokID}', 'Ostrovok@associate');
        });

        Route::prefix('booking')->group(function () {
          Route::get('', 'Booking@get');
          Route::post('', 'Booking@create');
          Route::post('start/{bookingID}', 'Booking@start');

          Route::get('confirm', 'Booking@confirm');
          Route::get('init', 'Booking@init');

          Route::get('rates', 'Booking@rates');
          Route::get('rooms', 'Booking@rooms');
          Route::get('rooms/upload', 'Booking@uploadRooms');

          Route::get('sync', 'Booking@syncRoomsAndRates');

          Route::post('premium_program', 'Booking@setPremiumProgram');

          Route::post('set_rate_mapping', 'Booking@setRateMapping');
          Route::post('set_room_mapping', 'Booking@setRoomMapping');
        });
      });
    });
  });
};
