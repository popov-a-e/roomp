<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSMSValidationsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('sms_validations', function (Blueprint $table) {
      $table->increments('id');

      $table->string('phone')->unique();
      $table->integer('code');

      $table->integer('attempts');
      $table->timestamp('last_attempt');
      $table->ipAddress('ip');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('sms_validations');
  }
}
