<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoompRateTypeToHotel extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hotels', function(Blueprint $table) {
      $table->boolean('dynamic_roomp_rate')->default(false);
      $table->unsignedInteger('dynamic_roomp_rate_discount')->nullable();
      $table->unsignedInteger('dynamic_roomp_rate_price_margin')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hotels', function(Blueprint $table) {
      $table->dropColumn('dynamic_roomp_rate', 'dynamic_roomp_rate_discount', 'dynamic_roomp_rate_price_margin');
    });
  }
}
