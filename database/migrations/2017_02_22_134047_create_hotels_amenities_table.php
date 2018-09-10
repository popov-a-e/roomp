<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsAmenitiesTable extends Migration {
  public function up() {
    Schema::create('hotels_amenities', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotel_id');
      $table->unsignedInteger('amenity_id');

      $table->index('hotel_id');
    });
  }

  public function down() {
    Schema::dropIfExists('hotels_amenities');
  }
}
