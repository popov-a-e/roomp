<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmedToReservation extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('reservations', function(Blueprint $table) {
      $table->boolean('channel_confirmed')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('reservations', function(Blueprint $table) {
      $table->dropColumn('channel_confirmed');
    });
  }
}
