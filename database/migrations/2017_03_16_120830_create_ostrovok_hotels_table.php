<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOstrovokHotelsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('ostrovok__hotels', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');
      $table->unsignedInteger('ostrovok_id')->unique();
      $table->unsignedInteger('hotel_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('ostrovok__hotels');
  }
}
