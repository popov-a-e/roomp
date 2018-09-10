<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveOfferToHotelFromHotelier extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotel_offers', function(Blueprint $table) {
      $table->renameColumn('hotelier_id', 'hotel_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotel_offers', function(Blueprint $table) {
      $table->renameColumn('hotel_id', 'hotelier_id');
    });
  }
}
