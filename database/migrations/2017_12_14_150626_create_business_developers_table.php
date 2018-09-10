<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessDevelopersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('business_developers', function (Blueprint $table) {
      $table->increments('id');

      $table->string('name');
      $table->string('password');

      $table->string('email')->unique();
      $table->string('phone');

      $table->string('remember_token')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('business_developers');
  }
}
