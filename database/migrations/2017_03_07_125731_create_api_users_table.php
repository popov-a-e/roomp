<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiUsersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('api_users', function (Blueprint $table) {
      $table->increments('id');
      $table->string('login')->unique();
      $table->string('name')->nullable();
      $table->string('password');
      $table->timestamps();

      $table->index('login');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('api_users');
  }
}
