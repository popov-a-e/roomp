<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLcodeToWubookRooms extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__rooms', function(Blueprint $table) {
      $table->unsignedInteger('lcode')->default(1501655009);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('wubook__rooms', function(Blueprint $table) {
      $table->dropColumn('lcode');
    });
  }
}
