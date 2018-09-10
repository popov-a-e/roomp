<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferBroken extends Migration {
  public function up() {
    Schema::table('hotel_offers', function(Blueprint $table) {
      $table->date('terminated_at')->nullable();
    });
  }

  public function down() {
    Schema::table('hotel_offers', function(Blueprint $table) {
      $table->dropColumn('terminated_at');
    });
  }
}
