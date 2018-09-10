<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricesGuestsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('prices_guests', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('price_id');
      
      $table->unsignedInteger('price');

      $table->unsignedInteger('guest_number');

      $table->index('price_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('prices_guests');
  }
}
