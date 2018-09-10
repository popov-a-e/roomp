<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeHotelIdRedundantInArrays extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('availability', function(Blueprint $table) {
      $table->unsignedInteger('hotel_id')->nullable(true)->change();
    });

    Schema::table('prices', function(Blueprint $table) {
      $table->unsignedInteger('hotel_id')->nullable(true)->change();
    });

    Schema::table('restrictions', function(Blueprint $table) {
      $table->unsignedInteger('hotel_id')->nullable(true)->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
  }
}
