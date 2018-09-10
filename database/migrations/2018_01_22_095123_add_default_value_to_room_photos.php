<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValueToRoomPhotos extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('rooms', function(Blueprint $table) {
      $table->text('photos')->default('')->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('rooms', function(Blueprint $table) {
      $table->text('photos')->default(null)->change();
    });
  }
}
