<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookConnectionsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('wubook__connections', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id')->index();
      $table->unsignedInteger('chid')->nullable();
      $table->unsignedInteger('booking_hotel_id')->nullable();

      $table->unsignedInteger('rate_id')->nullable();
      $table->boolean('rate_assigned')->default(false);
      $table->boolean('channel_confirmed')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('wubook__connections');
  }
}
