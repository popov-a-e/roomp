<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRoomPhotosBehaviour extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('rooms', function(Blueprint $table) {
      $table->text('photos')->default('')->nullable(true)->change();
      $table->json('photos_array')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('rooms', function(Blueprint $table) {
      $table->dropColumn('photos_array');
    });
  }
}
