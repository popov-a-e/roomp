<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessDeveloperToHotel extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('business_developer_hotels', function(Blueprint $table) {
      $table->increments('id');

      $table->enum('stage', ['initial', 'signing', 'active'])->default('initial');
      $table->text('comment')->nullable();
      $table->unsignedInteger('lead_id')->nullable();

      $table->unsignedInteger('hotel_id')->index();
      $table->foreign('hotel_id')
        ->references('id')->on('hotels')
        ->onDelete('cascade');
    });

    Schema::table('hotels', function(Blueprint $table) {
      $table->unsignedInteger('hotelier_id')->nullable()->change();
      $table->boolean('disabled')->default(true)->change();

      $table->float('lat', 100)->nullable()->change();
      $table->float('lng', 100)->nullable()->change();

      $table->unsignedInteger('city_id')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('business_developer_hotels');
  }
}
