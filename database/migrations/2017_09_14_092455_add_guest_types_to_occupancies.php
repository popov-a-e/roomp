<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGuestTypesToOccupancies extends Migration {
  public function up() {
    Schema::table('occupancy', function (Blueprint $table) {
      $table->dropColumn('guest_number');

      $table->unsignedInteger('adults')->default(0);
      $table->unsignedInteger('children')->default(0);
      $table->unsignedInteger('infants')->default(0);
    });
  }

  public function down() {
    Schema::table('occupancy', function (Blueprint $table) {
      $table->unsignedInteger('guest_number')->default(0);

      $table->dropColumn('adults');
      $table->dropColumn('children');
      $table->dropColumn('infants');
    });
  }
}
