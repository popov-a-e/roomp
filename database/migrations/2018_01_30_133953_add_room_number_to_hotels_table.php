<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoomNumberToHotelsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->unsignedInteger('room_number')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->dropColumn('room_number');
    });
  }
}
