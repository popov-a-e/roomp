<?php

namespace Roomp\Drivers\PDF;

use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf as Document;
use Dompdf\Options;

class PDF {
  private $options, $document;

  public function __construct(Options $options) {
    $this->options = $options;

    $options->set('isPhpEnabled', TRUE);

    $document = new Document($options);

    $document->setPaper("A4");
    $document->set_option('isHtml5ParserEnabled', true);


    $this->document = $document;
  }

  public function loadHTML($html) {
    $this->document->loadHtml($html);
    $this->document->render();

    return $this;
  }

  public function output() {
    $out = $this->document->output();

    $document = new Document($this->options);

    $document->setPaper("A4");
    $document->set_option('isHtml5ParserEnabled', true);


    $this->document = $document;

    return $out;
  }

  public function addPageCount() {
    $canvas = $this->document->get_canvas();
    //  $font = Font_Metrics::get_font("helvetica", "bold");
    $canvas->page_text(250, 800, "Страница: {PAGE_NUM} из {PAGE_COUNT}", 'dejavu sans', 10, array(0,0,0));
  }
}