<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdateCheckingToReservation extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('reservations', function(Blueprint $table) {
      $table->string('hash')->nullable();
      $table->timestamp('updated_reservation_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('reservations', function(Blueprint $table) {
      $table->dropColumn('updated_reservation_at', 'hash');
    });
  }
}
