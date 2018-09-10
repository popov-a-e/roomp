<?php

namespace Roomp\Http\Controllers\Admin;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Roomp\Drivers\Excel\Creator;
use Roomp\Drivers\OTA\Ostrovok\LegacyDriver;
use Roomp\Drivers\PDF\PDF;
use Roomp\Mail\MonthlyFinanceMail;
use Roomp\Mail\ReservationCancelMail;
use Roomp\Mail\ReservationHotelierCancelMail;
use Roomp\Mail\ReservationHotelierMail;
use Roomp\Mail\ReservationMail;
use Roomp\Repositories\CommonRepository;
use Roomp\Http\Controllers\Controller;
use Roomp\Repositories\ReservationRepository;
use Roomp\Repositories\HotelRepository;
use Roomp\Repositories\RoomRepository;
use Roomp\Repositories\UserRepository;
use Roomp\Reservation;
use Roomp\User;
use morphos;

class ReservationController extends Controller {
  private $repository;
  private $hotelRepository;
  private $userRepository, $pdf;

  public function __construct(ReservationRepository $repository, HotelRepository $hotelRepository, UserRepository $userRepository, PDF $pdf) {
    $this->repository = $repository;
    $this->hotelRepository = $hotelRepository;
    $this->userRepository = $userRepository;
    $this->pdf = $pdf;
  }

  public function getAll(Request $request) {

    $restrictions = $request->all();

    return $this->repository->allVerbose($restrictions);
  }

  public function getOne($code) {
    return $this->repository->oneVerbose($code);
  }

  public function getHotels() {
    return $this->hotelRepository->all()->pluck('name', 'id');
  }

  public function getRooms(Request $request, RoomRepository $repository, CommonRepository $commonRepository) {
    $hotelID = $request->input('hotel_id');
    $from = $request->input('from');
    $till = $request->input('till');

    $allotments = $commonRepository->getAllotments();

    $currency = $this->hotelRepository->find($hotelID)->currency;

    return $repository->getFree($hotelID, $from, $till, $currency)->map(function($room) use ($allotments)  {
      $room->allotments = collect($room->allotments)->map(function($a) use ($allotments) {
        $a = collect($allotments)->where('id', $a)->first();

        return $a;
      });

      return $room;
    });
  }

  public function create(Request $request, ReservationRepository $repository) {
    $data = $request->all();

    $user = $this->userRepository->getOrRegister($data);

    return $repository->create($data, $user);
  }

  public function addComment($code, Request $request) {
    return $this->repository->addComment($code, $request->input('admin_comment'));
  }

  public function miss($code) {
    $this->repository->missReservation($code);
  }

  public function cancel(Reservation $reservation) {
    $reservation->cancel();
  }

  public function overbooking($code) {
    $reservation = $this->repository->getBycode($code);

    $reservation->overbooking();

    return '';
  }

  public function resendEmail($code) {
    $reservation = $this->repository->getByCode($code);

    if ($reservation->status->status->active) {
      $mail = new ReservationMail($reservation);
    } else {
      $mail = new ReservationCancelMail($reservation);
    }

    if (!$reservation->creator) {
      abort(400, "Бронирование не было создано самим пользователем и его e-mail неизвестен");
    }

    return Mail::to($reservation->creator->email)
      ->send($mail);
  }

  public function resendEmailToHotelier($code) {
    $reservation = $this->repository->getByCode($code);

    if ($reservation->status->status->active) {
      $mail = new ReservationHotelierMail($reservation);
    } else {
      $mail = new ReservationHotelierCancelMail($reservation);
    }

    return Mail::to($reservation->hotel->receptionEmails)->send($mail);
  }

  public function generateReport(Request $request, Creator $creator) {
    $IDs = $request->input('reservations');

    $reservations = $this->repository->generateFullReportData($IDs);

    return $creator->generateFullReport($reservations);
  }

  public function getReport($name) {
    return redirect(Storage::temporaryUrl("reports/$name", now()->addMinute()));
  }

  public function generateHotelReport(Request $request, Creator $creator) {
    $hotelID = $request->input('hotel_id');
    $month = $request->input('month');
    $year = $request->input('year');

    $doc = $this->repository->reconcilliationDoc($hotelID, $month, $year);

    if ($doc->file && !$request->input('repeat')) abort(403, "Документ уже создан");

    $hotel = $this->hotelRepository->find($hotelID);

    $reconcilliationData = $this->repository->reconcilliation($hotelID, $month, $year)->pluck('id')->values()->toArray();

    $filename = $creator->generateHotelReport($hotel->organization->local, $this->repository->generateReportData($reconcilliationData), $doc, $hotel);

    $this->repository->attachReconcilliationFile($doc->id, $filename);
  }

  public function generateDocs(Request $request, Creator $creator) {
    $hotelID = $request->input('hotel_id');
    $month = $request->input('month');
    $year = $request->input('year');

    $hotel = $this->hotelRepository->find($hotelID);

    $doc = $this->repository->reconcilliationDoc($hotelID, $month, $year);

    $reservations = $this->repository->reconcilliation($hotelID, $month, $year);
    $credit = $reservations->filter(function ($r) {
      return $r->status_name === 'Онлайн' || $r->status_name === 'Вирт. карта';
    })->reduce(function ($sum, $r) {
      return $sum + $r->total;
    }, 0);

    $total = $reservations->reduce(function ($sum, $r) {
      return $sum + $r->total;
    }, 0);

    $debit = $reservations->reduce(function ($sum, $r) {
      return $sum + $r->total - $r->rate;
    }, 0);

    $reconcilliationData = $reservations->pluck('id')->values()->toArray();

    $creator->generateHotelReport($hotel->organization->local, $this->repository->generateReportData($reconcilliationData), $doc, $hotel);

    $sum = abs($debit - $credit);

    $this->generateAct($hotel, $debit, $year, $month);
    $this->generateReconcilliationAct($hotel, $debit, $credit, $year, $month);
    $this->generateInvoice($hotel, $sum, $year, $month);

    $this->repository->confirmDocsGenerated($doc->id, $total, $debit, $credit);
  }

  public function sendEmail(Request $request) {
    $hotelID = $request->input('hotel_id');
    $month = $request->input('month');
    $year = $request->input('year');

    $hotel = $this->hotelRepository->find($hotelID);

    $reservations = $this->repository->reconcilliation($hotelID, $month, $year);
    $credit = $reservations->filter(function($r) {
      return $r->status_name === 'Онлайн' || $r->status_name === 'Вирт. карта';
    })->reduce(function($sum, $r) {return $sum + $r->total;}, 0);
    $debit = $reservations->reduce(function($sum, $r) {return $sum + $r->total - $r->rate;}, 0);

    $bill = $debit - $credit > 0;

    return Mail::to($hotel->receptionEmails)->send(new MonthlyFinanceMail($hotelID, $month, $year, $bill));
  }

  private function generateAct($hotel, $sum, $year, $month) {
    $hotelID = $hotel->id;
    $organization = $hotel->organization->local;
    $offer = $hotel->offer;

    $view = ($offer->accept_date ?? false) ? 'docs.act.index' : 'docs.old.act.index';

    $this->pdf->loadHtml(view($view, compact('organization', 'sum', 'month', 'year', 'hotelID', 'offer')));

    $this->putDocument($year, $month, $hotelID, 'act', "A$hotelID-$year/$month.pdf", $this->pdf->output());
  }


  private function generateReconcilliationAct($hotel, $debit, $credit, $year, $month) {
    $hotelID = $hotel->id;
    $organization = $hotel->organization->local;
    $offer = $hotel->offer;

    $name = $organization->CEO;
    $nameParts = explode(' ',$name);

    if (count($nameParts) > 3) {
      $extra = count($nameParts) - 3;

      $name = '';

      for($i = 0; $i <= $extra; ++$i) {
        $name .= $nameParts[$i];
        if ($i < $extra) $name .= '#';
      }

      $name .= ' '.$nameParts[$extra + 1].' '.$nameParts[$extra + 2];
    }

    $gender = morphos\Russian\detectGender($name);
    $inflectedCEO = morphos\Russian\inflectName($name, 'родительный');

    $inflectedCEO = preg_replace('|#|im', ' ', $inflectedCEO);

    $view = ($offer->accept_date ?? false) ? 'docs.reconciliation_act.index' : 'docs.old.reconciliation_act.index';
    $this->pdf->loadHtml(view($view, compact('gender', 'inflectedCEO', 'organization', 'sum', 'month', 'year', 'hotel', 'hotelID', 'offer', 'credit', 'debit')));

    $this->putDocument($year, $month, $hotelID, 'reconciliation_act',"R$hotelID-$year/$month.pdf", $this->pdf->output());
  }

  private function generateInvoice($hotel, $sum, $year, $month) {
    $hotelID = $hotel->id;
    $organization = $hotel->organization->local;
    $offer = $hotel->offer;

    $view = ($offer->accept_date ?? false) ? 'docs.invoice.index' : 'docs.old.invoice.index';

    $this->pdf->loadHtml(view($view, compact('organization', 'sum', 'month', 'year', 'hotelID', 'offer')));

    $this->putDocument($year, $month, $hotelID, 'invoice', "B$hotelID-$year/$month.pdf", $this->pdf->output());
  }

  private function putDocument($year, $month, $hotelID, $type, $filename, $content) {
    Storage::put("finance/$year/$month/$hotelID/$type.pdf", $content, [
      'ContentDisposition' => "inline; filename=\"$filename\""
    ]);
  }

  public function reconcilliation(Request $request) {
    $hotelID = $request->input('hotel_id');
    $month = $request->input('month');
    $year = $request->input('year');

    $privileged = DB::table('wubook__connections')->where('hotel_id', $hotelID)->first()->premium_program ?? false;
    $reservations = $this->repository->reconcilliation($hotelID, $month, $year, true);
    $fund = $this->hotelRepository->find($hotelID)->rooms->reduce(function($p, $c) {
      return $p + $c->number;
    }, 0) *  cal_days_in_month(CAL_GREGORIAN, $month, $year);

    $ostrovokNights = $reservations->where('active', true)->where('channel_name', 'Ostrovok')->sum('nights');

    $ostrovokLoad = $ostrovokNights / $fund;

    switch (true) {
      case $ostrovokLoad < 0.1: $ostrovokCommission = .145; break;
      case $ostrovokLoad < 0.15: $ostrovokCommission = .18; break;
      case $ostrovokLoad < 0.2: $ostrovokCommission = .2; break;
      case $ostrovokLoad < 0.25: $ostrovokCommission = .22; break;
      case $ostrovokLoad < 0.3: $ostrovokCommission = .24; break;
      default: $ostrovokCommission = .26; break;
    }

    return [
      'reservations' => $reservations,
      'booking_commission' => $privileged ? .18 : .15,
      'ostrovok_commission' => $ostrovokCommission,
    ];
  }

  public function reconcilliationReport(Request $request) {
    return $this->repository->reconcilliationReport($request->input('month'),$request->input('year'));
  }

  public function reconcilliationDoc(Request $request) {
    $hotelID = $request->input('hotel_id');
    $month = $request->input('month');
    $year = $request->input('year');

    return $this->repository->reconcilliationDoc($hotelID, $month, $year);
  }

  public function reconcilliationDocument($year, $month, $hotelID, $type) {
    $name = $type === 'report' ? 'report.xlsx' : "$type.pdf";
    if (!Storage::exists("finance/$year/$month/$hotelID/$name")) return "File does not exist";

    return redirect(Storage::temporaryUrl("finance/$year/$month/$hotelID/$name", now()->addMinute()));
  }

  public function getOccupancies($id) {
    return $this->repository->getReservationOccupancies($id);
  }

  public function setOccupancy(Request $request) {
    $this->repository->setOccupancy((object)$request->all());
  }

  public function getOstrovokPhone($code, LegacyDriver $driver) {
    $reservation = $this->repository->getByCode($code);

    return $driver->getPhone($reservation);
  }

  public function pushStatus($code, Request $request) {
    $online = !!filter_var($request->input('online'), FILTER_VALIDATE_BOOLEAN);

    $reservation = $this->repository->getByCode($code);

    $status = $reservation->status->status;

    $reservation->mutateState(true, $online, $status->confirmed);

    return '';
  }

  public function channelPush($code) {
    Artisan::call('push_force', ['id' => $this->repository->getByCode($code)->id]);
  }

  public function delete($code) {
    $this->repository->getByCode($code)->delete();
  }

  public function edit(Request $request) {
    $this->repository->edit((object) $request->all());
  }
}
