<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoodyBagTimestamp extends Migration
{
  public function up() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->timestamp('goody_bags_timestamp')->nullable();
    });
  }

  public function down() {
    Schema::table('hotels', function (Blueprint $table) {
      $table->dropColumn('goody_bags_timestamp');
    });
  }
}
