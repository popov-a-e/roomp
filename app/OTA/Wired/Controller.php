<?php

namespace Roomp\OTA\Wired;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Roomp\Facades\SMS;
use Roomp\Http\Controllers\Controller as BaseController;
use Roomp\Repositories\NotEnoughSpaceException;

class Controller extends BaseController {
  public function push(Request $request, Repository $repository) {
    $lcode = $request->input('lcode');
    $rcode = $request->input('rcode');
    file_put_contents('/var/log/roomp/wubook.log', "{$lcode}, {$rcode}", FILE_APPEND);

    try {
      $repository->handleReservation($lcode, $rcode);
      file_put_contents('/var/log/roomp/wubook.log', ": success" . "\r\n", FILE_APPEND);
    } catch (NotEnoughSpaceException $exception) {
      Mail::send('errors.not_enough_space', ['reservation' => json_encode($exception->reservation)], function ($message) {
        $message->to('admin@roomp.co', 'Admin')->subject('Ошибка в WuBook - попытка забронировать занятый номер.');
      });

      file_put_contents('/var/log/roomp/wubook.log', ": " . $exception->getMessage() . "\r\n\r\n", FILE_APPEND);
    }

    return '';
  }
}
