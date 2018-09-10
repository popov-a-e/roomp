<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActivityToHoteliers extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hoteliers', function (Blueprint $table) {
      $table->timestamp('last_activity')->nullable();
      $table->string('last_action')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hoteliers', function (Blueprint $table) {
      $table->dropColumn('last_activity')->nullable();
      $table->dropColumn('last_action')->nullable();
    });
  }
}
