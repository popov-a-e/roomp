<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstrovokRatesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('ostrovok__rates', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('ostrovok_id');
      $table->string('name');

      $table->unsignedInteger('ostrovok_room_id');

      $table->index('ostrovok_room_id');
      $table->index('ostrovok_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('ostrovok__rates');
  }
}
