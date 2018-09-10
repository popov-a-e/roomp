<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelPricesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('channel_prices', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->float('price')->default(100000);

      $table->unsignedInteger('room_id');
      $table->unsignedInteger('rate_id');

      $table->unsignedInteger('hotel_id');
      $table->unsignedInteger('guest_number')->default(1)->index();

      $table->index('date');
      $table->index('hotel_id');

      $table->index(['room_id', 'rate_id']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('channel_prices');
  }
}
