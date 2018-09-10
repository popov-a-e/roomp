<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoteliersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hoteliers', function (Blueprint $table) {
      $table->increments('id');
      $table->string('name');
      $table->string('password');
      $table->string('phone');
      $table->string('email')->unique();
      $table->string('organization');
      $table->rememberToken();
      $table->timestamp('last_seen')->default(DB::raw('CURRENT_TIMESTAMP'));
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hoteliers');
  }
}
