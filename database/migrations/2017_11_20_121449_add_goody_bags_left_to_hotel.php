<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodyBagsLeftToHotel extends Migration {
  public function up() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->integer('goody_bags_left')->default(0);
    });
  }

  public function down() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->dropColumn('goody_bags_left');
    });
  }
}
