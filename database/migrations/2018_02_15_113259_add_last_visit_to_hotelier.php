<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastVisitToHotelier extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hoteliers', function(Blueprint $table) {
      $table->dateTime('last_visit')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hoteliers', function(Blueprint $table) {
      $table->dropColumn('last_visit');
    });
  }
}
