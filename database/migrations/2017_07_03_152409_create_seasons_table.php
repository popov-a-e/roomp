<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeasonsTable extends Migration {
  public function up() {
    Schema::create('seasons', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedInteger('hotel_id')->index();

      $table->string('name')->nullable();
      $table->string('rule')->nullable();
      $table->string('color');

      $table->boolean('default')->default(false);

      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('seasons');
  }
}
