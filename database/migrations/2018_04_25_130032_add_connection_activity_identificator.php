<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConnectionActivityIdentificator extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__connections', function(Blueprint $table) {
      $table->timestamp('last_active')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__connections', function(Blueprint $table) {
      $table->dropColumn('last_active');
    });
  }
}
