<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('prices', function (Blueprint $table) {
      $table->increments('id');
      $table->date('date');
      $table->unsignedInteger('price');

      $table->unsignedInteger('room_id');

      $table->unsignedInteger('hotel_id');

      $table->index('date');
      $table->index('hotel_id');

      $table->index(['hotel_id','price']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('prices');
  }
}
