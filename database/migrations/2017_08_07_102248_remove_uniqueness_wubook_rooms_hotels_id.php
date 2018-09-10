<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveUniquenessWubookRoomsHotelsId extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('wubook__rooms', function (Blueprint $table) {
      $table->unsignedInteger('wubook_hotel_id')->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    /*
     * Schema::table('wubook__rooms', function (Blueprint $table) {
      $table->dropColumn('wubook_hotel_id');
    });*/
  }
}
