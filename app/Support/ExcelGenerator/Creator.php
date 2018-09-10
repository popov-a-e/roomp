<?php
/**
 * Created by PhpStorm.
 * User: Thunder
 * Date: 20.10.2017
 * Time: 14:34
 */

namespace Roomp\Drivers\Excel;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PHPExcel_Reader_Excel2007;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet;
use PHPExcel_Writer_Excel2007;

class Creator {
  private $page;
  private $file;

  public function __construct() {
  }

  public function insertReservations($reservations) {
    $offlineRowInit = 20;
    $onlineRowInit = 24;

    $offlineRow = $offlineRowInit;
    $onlineRow = $onlineRowInit;

    $onlineCount = 0;
    $offlineCount = 0;

    $reservations->each(function ($reservation) use (&$onlineRow, &$offlineRow, &$onlineRowInit, &$onlineCount, &$offlineCount) {
      $onlineRow++;
      $row = $onlineRow;

      if (!$reservation['online']) {
        $offlineRow++;
        $onlineRowInit++;
        $row = $offlineRow;
        $offlineCount++;
        $count = $offlineCount;
      } else {
        $onlineCount++;
        $count = $onlineCount;
      }

      $this->insertReservationData($row, $reservation, $count);
    });

    //Total sum calculation
    if ($offlineRow > $offlineRowInit) {
      $this->setSumValue(5, $offlineRowInit + 1, $offlineRow);
      $this->setSumValue(6, $offlineRowInit + 1, $offlineRow);
      $this->setSumValue(7, $offlineRowInit + 1, $offlineRow);
    }

    if ($onlineRow > $onlineRowInit) {
      $this->setSumValue(5, $onlineRowInit + 1, $onlineRow);
      $this->setSumValue(6, $onlineRowInit + 1, $onlineRow);
      $this->setSumValue(7, $onlineRowInit + 1, $onlineRow);
    }

    $this->setTotalValue(5, $onlineRow + 1, $offlineRow + 1);
    $this->setTotalValue(6, $onlineRow + 1, $offlineRow + 1);
    $this->setTotalValue(7, $onlineRow + 1, $offlineRow + 1);
  }

  public function hotelsList($hotels) {
    $this->file = new PHPExcel_Writer_Excel2007();

    $this->page = $this->file->setActiveSheetIndex(0);

    $this->insertHotels($hotels);

    return $this->saveFile();
  }

  private function insertHotels($hotels) {
    $hotels->each();
  }

  public function generateFullReport($reservations) {
    $this->file = (new \PHPExcel());

    $this->file->getProperties()
      ->setCreator(Auth::guard('admins')->user()->name ?? '')
      ->setLastModifiedBy(Auth::guard('admins')->user()->name ?? '')
      ->setTitle("Reservations report")
      ->setSubject("Reservations report")
      ->setDescription("Reservations report")
      ->setKeywords("Reservations report");

    $this->page = $this->file->setActiveSheetIndex(0);

    $this->insertFullReservations($reservations);

    return $this->saveFile();
  }

  public function generateHotelReport($requisites, $reservations, $document, $hotel) {
    if ($hotel->offer && $hotel->offer->accept_date) {
      $this->file = $this->prepareFile('resources/views/docs/report/report.xlsx');
    } else {
      $this->file = $this->prepareFile('resources/views/docs/old/report/report.xlsx');
    }

    $this->page = $this->file->setActiveSheetIndex(0);

    $this->insertRequisites($requisites);
    $this->insertReservations($reservations);

    $dates = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря'];

    $month = $dates[$document->month - 1];
    $maxDate = date('t', strtotime("{$document->year}-{$document->month}-01"));
    $dateString = "за период с «1» $month {$document->year} года по «{$maxDate}» $month {$document->year} года";

    $this->page->setCellValue('A18', $dateString);

    $this->page->setCellValue('B5', "г. {$hotel->city->ru}");

    $date = $document->updated_at->format('d/m/Y');

    $year = $document->year;
    $month = $document->month;
    $hotelID = $hotel->id;

    $headerStr = "№O{$hotelID}-{$year}/{$month} от {$date}";

    $sigStr = "  ___________/ {$requisites->CEO_short_name} /";

    $this->page->setCellValueByColumnAndRow(4, 1, $headerStr);
    $this->page->setCellValueByColumnAndRow(2, $reservations->count() + 35, $sigStr);

    //$dir = base_path("storage/app/finance/$year/$month/$hotelID/");

    $objWriter = PHPExcel_IOFactory::createWriter($this->file, "Excel2007");

    ob_start();

    $objWriter->save("php://output");

    $file = ob_get_clean();

    Storage::put("finance/$year/$month/$hotelID/report.xlsx", $file, [
      'ContentDisposition' => "inline; filename=\"O$hotelID-$year/$month.xlsx\""
    ]);

    return true;
  }

  private function insertRequisites($requisites) {
    $this->page->setCellValueByColumnAndRow(2, 7, $requisites->form . " " . $requisites->name);
    if ($requisites->KPP) {
      $this->page->setCellValueByColumnAndRow(3, 7, "ИНН/КПП: " . $requisites->INN . '/' . $requisites->KPP);
    } else {
      $this->page->setCellValueByColumnAndRow(3, 7, "ИНН: " . $requisites->INN);
    }
    if ($requisites->legal_address) $this->page->setCellValueByColumnAndRow(2, 8, "Юридический адрес: " . $requisites->legal_address);
    if ($requisites->fact_address) $this->page->setCellValueByColumnAndRow(2, 9, "Фактический адрес: " . $requisites->fact_address);
    if ($requisites->post_address) $this->page->setCellValueByColumnAndRow(2, 10, "Почтовый адрес: " . $requisites->post_address);
  }

  public function generateReport($reservations) {
    $this->insertReservations($reservations);

    return $this->saveFile();
  }

  private function setSumValue($column, $first, $last) {
    $firstCell = $this->page->getCellByColumnAndRow($column, $first)->getCoordinate();
    $lastCell = $this->page->getCellByColumnAndRow($column, $last)->getCoordinate();

    $this->page->setCellValueByColumnAndRow($column, $last + 1, "=SUM($firstCell:$lastCell)");
  }

  private function setTotalValue($column, $online, $offline) {
    $onlineCell = $this->page->getCellByColumnAndRow($column, $online)->getCoordinate();
    $offlineCell = $this->page->getCellByColumnAndRow($column, $offline)->getCoordinate();

    $this->page->setCellValueByColumnAndRow($column, $online + 1, "=$onlineCell + $offlineCell");
  }

  private function prepareFile($location = null) {
    if ($location === null) $location = base_path('report.xlsx');
    else $location = base_path($location);
    $reader = new PHPExcel_Reader_Excel2007();

    return $reader->load($location);
  }

  private function saveFile() {
    $name = str_random() . '.xlsx';
//    $objWriter = PHPExcel_IOFactory::createWriter($this->file, "Excel2007");
//    $objWriter->save(base_path('storage/app/public/reports/' . $name));
//
//    return $name;

    $objWriter = PHPExcel_IOFactory::createWriter($this->file, "Excel2007");

    ob_start();

    $objWriter->save("php://output");

    $file = ob_get_clean();

    Storage::put("reports/$name", $file);

    return $name;
  }

  private function insertReservationData($row, $reservation, $count) {
    $fields = ['code', 'period', 'guest_name', 'hotel', 'total', 'roomp_rate', 'hotel_rate'];

    $this->page->insertNewRowBefore($row);

    $this->page->setCellValueByColumnAndRow(0, $row, $count);

    for ($i = 0; $i < count($fields); $i++) {
      $field = $fields[$i];
      $this->page->setCellValueByColumnAndRow($i + 1, $row, $reservation[$field]);
    }
  }

  public function insertStampAndSignature() {
    //
  }

  public function insertFullReservations($reservations) {
    $fields = ['reservation_id', 'reservation_date', 'check_in', 'check_out', 'rooms', 'nights', 'rooms_nights', 'hotel_proceeds', 'total_sum', 'status', 'channel', 'hotel_name', 'city', 'guest_name', 'payment_method', 'guest_phone', 'goody_bags', 'admin_comment'];

    $this->page->setCellValueByColumnAndRow(0, 1, '#');

    foreach ($fields as $key => $value) {
      $this->page->setCellValueByColumnAndRow($key + 1, 1, $value);
    }

    $reservations->each(function ($reservation, $i) use ($fields) {
      $this->insertFullReservationData($reservation, $i + 2);
    });
  }

  public function insertFullReservationData($reservation, $row) {
    $fields = ['reservation_id', 'reservation_date', 'check_in', 'check_out', 'rooms', 'nights', 'rooms_nights', 'hotel_proceeds', 'total_sum', 'status', 'channel', 'hotel_name', 'city', 'guest_name', 'payment_method', 'guest_phone', 'goody_bags', 'admin_comment'];

    $this->page->setCellValueByColumnAndRow(0, $row, $row - 1);

    for ($i = 0; $i < count($fields); $i++) {
      $field = $fields[$i];
      if ($field === 'rooms_nights') {
        $rooms = $this->page->getCellByColumnAndRow($i - 1, $row)->getCoordinate();
        $nights = $this->page->getCellByColumnAndRow($i, $row)->getCoordinate();

        $this->page->setCellValueByColumnAndRow($i + 1, $row, "=$rooms*$nights");
      } else {
        $this->page->setCellValueByColumnAndRow($i + 1, $row, $reservation[$field]);
      }
    }
  }

  public function createBooking() {
    $file = new \PHPExcel();

    $rows = \DB::table('booking__statistics')->get();

    $from = $rows->min('date');
    $till = $rows->max('date');

    $rows = $rows->groupBy('hotel_id');

    foreach (['w_position', 'pageviews', 'impressions', 'bookings'] as $index => $key) {
      $file->createSheet($index);

      $page = $file->setActiveSheetIndex($index);
      $page->setTitle($key);

      for ($i = $from, $column = 1; $i <= $till; $i = date('Y-m-d', strtotime($i . "+1 day")), $column++) {
        $page->setCellValueByColumnAndRow($column, 1, $i);

        $rowN = 2;
        $rows->each(function ($hotelRows, $hotelID) use (&$rowN, $column, $page, $i, $key) {
          $row = $hotelRows->where('date', $i)->first();

          $page->setCellValueByColumnAndRow(0, $rowN, $hotelID);
          $page->setCellValueByColumnAndRow($column, $rowN, $row->$key ?? '');
          $rowN ++;
        });
      }
    }

    $objWriter = PHPExcel_IOFactory::createWriter($file, "Excel2007");

    ob_start();

    $objWriter->save("php://output");

    $file = ob_get_clean();

    Storage::put('reports/bcom_report.xlsx', $file);

    return 'bcom_report.xlsx';
  }
}