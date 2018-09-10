<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBreakfastPriceToPolicy extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('policies', function(Blueprint $table) {
      $table->float('breakfast_price')->default(0);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('policies', function(Blueprint $table) {
      $table->dropColumn('breakfast_price');
    });
  }
}
