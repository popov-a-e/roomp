<?php
/**
 * Created by PhpStorm.
 * User: user2
 * Date: 29.01.2018
 * Time: 12:02
 */

namespace Roomp\Drivers\PDF;


use Illuminate\Support\Facades\Storage;

class DocsGenerator {
  private $pdf;

  public function __construct(PDF $PDF) {
    $this->pdf = $PDF;
  }

  public function getPDF() {

    $params = [

    ];


    $html = view('docs.act.index', $params);
    return $html;
    $this->pdf->loadHTML($html);

    return $this->pdf->output();
  }

}