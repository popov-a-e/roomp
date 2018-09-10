<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReworkPriceTablesCompletely extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::dropIfExists('prices_guests');
    Schema::table('prices', function(Blueprint $table) {
      $table->unsignedInteger('guest_number')->default(1);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('prices', function(Blueprint $table) {
      $table->dropColumn('guest_number');
    });
  }
}
