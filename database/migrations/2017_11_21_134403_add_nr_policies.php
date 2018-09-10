<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNrPolicies extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('booking_policies', function(Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id')->index();
      $table->unsignedInteger('last_minute_days')->default(0);
      $table->unsignedInteger('long_stay_days')->default(15);

      $table->timestamps();
    });

    Schema::table('seasons', function(Blueprint $table) {
      $table->string('nr_discount')->default('0 %');
      $table->string('last_minute_discount')->default('0 %');
      $table->string('long_stay_discount')->default('0 %');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('booking_policies');

    Schema::table('seasons', function(Blueprint $table) {
      $table->dropColumn('nr_discount');
      $table->dropColumn('last_minute_discount');
      $table->dropColumn('long_stay_discount');
    });
  }
}
