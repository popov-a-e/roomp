<?php

use Illuminate\Support\Facades\Route;

return function () {
  Route::group(['prefix' => 'reservations'], function () {
    Route::get('', 'ReservationController@getAll');
    Route::post('', 'ReservationController@edit');
    Route::post('report', 'ReservationController@generateReport');
    Route::get('report/{name}', 'ReservationController@getReport');
    Route::get('hotels', 'ReservationController@getHotels');
    Route::get('rooms', 'ReservationController@getRooms');
    Route::post('create', 'ReservationController@create');
  });

  Route::group(['prefix' => '/reservation/{code}'], function () {
    Route::get('', 'ReservationController@getOne');
    Route::delete('', 'ReservationController@delete');
    Route::post('add_comment', 'ReservationController@addComment');
    Route::get('miss', 'ReservationController@miss');
    Route::get('cancel', 'ReservationController@cancel');
    Route::get('overbooking', 'ReservationController@overbooking');
    Route::get('resend_email', 'ReservationController@resendEmail');
    Route::get('resend_email_to_hotelier', 'ReservationController@resendEmailToHotelier');
    Route::get('get_ostrovok_phone', 'ReservationController@getOstrovokPhone');
    Route::get('push_status', 'ReservationController@pushStatus');
    Route::get('channel_push', 'ReservationController@channelPush');
  });

};