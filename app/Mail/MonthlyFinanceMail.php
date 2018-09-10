<?php

namespace Roomp\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\File;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MonthlyFinanceMail extends Mailable/* implements ShouldQueue*/
{
  use Queueable, SerializesModels;

  public $year;
  public $month;
  public $hotelID, $bill;

  public function __construct($hotelID, $month, $year, $bill) {
    $this->month = $month;
    $this->year = $year;
    $this->hotelID = $hotelID;
    $this->bill = $bill;

    $monthname = __('dates.full')[$month - 1];

    $this->from = [['address' => 'credit@roomp.co', 'name' => 'Отдел кредитного контроля Roomp']];
    $this->subject = "Закрывающие документы Roomp за $monthname $year";
  }

  public function build() {
    $this->attachData(
      Storage::get("finance/{$this->year}/{$this->month}/{$this->hotelID}/act.pdf"),
      "Акт об оказании услуг Roomp №A{$this->year}-{$this->month}/{$this->hotelID}.pdf",
      [
        'mime' => 'application/pdf',
      ]);

    if ($this->bill) {
      $this->attachData(
        Storage::get("finance/{$this->year}/{$this->month}/{$this->hotelID}/invoice.pdf"),
        "Счет Roomp №B{$this->year}-{$this->month}/{$this->hotelID}.pdf",
        [
          'mime' => 'application/pdf'
        ]);
    }

    $this->attachData(
      Storage::get("finance/{$this->year}/{$this->month}/{$this->hotelID}/report.xlsx"),
      "Отчет агента Roomp №O{$this->year}-{$this->month}/{$this->hotelID}.xlsx",
      [
        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      ]);

    $this->attachData(
      Storage::get("finance/{$this->year}/{$this->month}/{$this->hotelID}/reconciliation_act.pdf"),
      "Акт взаиморасчета Roomp №A{$this->year}-{$this->month}/{$this->hotelID}.pdf",
      [
        'mime' => 'application/pdf'
      ]);

    return $this
      ->withSwiftMessage(function ($message) {
        $headers = $message->getHeaders();
        $headers->addTextHeader('X-Mailgun-Track-Clicks', 'no');
      })
      ->markdown('email.hotelier.monthly_finance_docs');
  }
}
