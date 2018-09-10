<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelOffersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hotel_offers', function (Blueprint $table) {
      $table->increments('id');

      $table->date('accept_date')->nullable();
      $table->string('filename')->nullable();
      $table->string('hash')->nullable();
      $table->string('checksum')->nullable();
      $table->unsignedInteger('hotelier_id')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hotel_offers');
  }
}
