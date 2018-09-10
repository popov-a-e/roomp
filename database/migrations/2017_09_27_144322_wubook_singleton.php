<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WubookSingleton extends Migration {
  public function up() {
    Schema::dropIfExists('wubook__hotels');

    Schema::table('wubook__rooms', function(Blueprint $table) {
      $table->dropColumn('wubook_hotel_id');
    });
  }

  public function down() {
  }
}
