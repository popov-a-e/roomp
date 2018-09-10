<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToWubookRoom extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__rooms', function(Blueprint $table) {
      $table->string('code')->unique();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__rooms', function(Blueprint $table) {
      $table->dropColumn('code');
    });
  }
}
