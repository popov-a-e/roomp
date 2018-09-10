<?php
/**
 * Created by PhpStorm.
 * User: user2
 * Date: 29.01.2018
 * Time: 12:02
 */

namespace Roomp\Drivers\PDF;


use Illuminate\Support\Facades\Storage;

class OfferGenerator {
  private $pdf;

  public function __construct(PDF $PDF) {
    $this->pdf = $PDF;
  }

  public function getPDF($hotel) {
    $offer = $hotel->offer ?? null;

    $params = [
      'hotel' => $hotel,
      'organization' => $hotel->organization->local,
      'form' => $this->getForm($hotel->organization->form)
    ];

    if ($offer) {
      $params['id'] = $offer->id;
      if ($offer->accept_date) {
        $params['date'] = date('d.m.Y', strtotime($offer->accept_date));
      }
    }

    $html = view("extranet.offer_file.{$hotel->organization->locale}.index", $params);

    $this->pdf->loadHTML($html);

    return $this->pdf->output();
  }

  public function generate($hotel, $name = null) {
    Storage::put('offers/' . $filename = $name ?: str_random(64) . '.pdf', $this->getPDF($hotel), [
      'visibility' => 'private',
      'ContentDisposition' => "inline; filename=\"Roomp offer for {$hotel->regular_name}.pdf\""
    ]);

    return $filename;
  }

  private function getForm($form) {
    return [
      'ИП' => 'Индивидуальному предпринимателю',
      'ООО' => 'Обществу с ограниченной ответственностью',
      'АО' => 'Акционерному обществу'
    ][$form] ?? '';
  }
}