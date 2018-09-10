<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookReservationsTable extends Migration {
  public function up() {
    Schema::create('wubook__reservations', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('rcode')->index();

      $table->unsignedInteger('lcode')->index();

      $table->unsignedInteger('reservation_id')->index();

      $table->boolean('active')->default(true);
    });
  }

  public function down() {
    Schema::dropIfExists('wubook__reservations');
  }
}
