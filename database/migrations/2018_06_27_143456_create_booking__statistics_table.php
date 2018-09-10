<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingStatisticsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('booking__statistics', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id');
      $table->date('date');
      $table->unsignedInteger('w_position')->nullable();
      $table->unsignedInteger('pageviews')->nullable();
      $table->unsignedInteger('impressions')->nullable();
      $table->unsignedInteger('bookings')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('booking__statistics');
  }
}
