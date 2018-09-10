<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWubookHotelsTable extends Migration {
  public function up() {
    Schema::create('wubook__hotels', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('lcode')->unique();
      $table->unsignedInteger('hotel_id')->nullable();
    });
  }

  public function down() {
    Schema::dropIfExists('wubook__hotels');
  }
}
