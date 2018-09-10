<?php

namespace Roomp\Drivers\BCom;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Roomp\Drivers\Excel\Creator;
use Roomp\Http\Controllers\Controller as BaseController;

class Controller extends BaseController {
  private $excel;

  public function __construct(Creator $excel) {
    $this->excel = $excel;
  }

  public function options() {
    return response('', 200, [
      'Access-Control-Allow-Origin' => 'https://admin.booking.com',
      'Access-Control-Allow-Credentials' => 'true',
      'Access-Control-Allow-Headers' => '*'
    ]);
  }

  public function fill(Request $request) {
    $data = collect($request->all());

    $data->each(function($hotel, $hotelID) {
      $rows = collect($hotel->data ?? $hotel['data'] ?? [])->map(function($row) use ($hotelID) {
        $row = (object)$row;

        DB::table('booking__statistics')->where('hotel_id', $hotelID)->where('date', $row->yyyy_mm_dd)->delete();

        return [
          'hotel_id' => $hotelID,
          'bookings' => $row->bookings ?? null,
          'impressions' => $row->impressions ?? null,
          'pageviews' => $row->pageviews ?? null,
          'w_position' => $row->w_position ?? null,
          'date' => $row->yyyy_mm_dd ?? false
        ];
      })->filter(function($row) {return $row['date'];})->values()->toArray();

      DB::table('booking__statistics')->insert($rows);
    });

    return response('', 200, [
      'Access-Control-Allow-Origin' => 'https://admin.booking.com',
      'Access-Control-Allow-Credentials' => 'true',
      'Access-Control-Allow-Headers' => 'X-Booking-CSRF, Content-Type',
      'Content-Type' => 'text/plain'
    ]);
  }

  public function getFile(){
    $this->excel->createBooking();

    return redirect(Storage::temporaryUrl('reports/bcom_report.xlsx', now()->addMinutes(5)));
  }
}
