<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsClosestTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hotels_closest', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id');
      $table->unsignedInteger('closest_id');

      $table->index('hotel_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hotels_closest');
  }
}
