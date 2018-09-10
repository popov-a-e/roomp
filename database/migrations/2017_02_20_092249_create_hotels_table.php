<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hotels', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('hotelier_id');
      $table->unsignedInteger('city_id');

      $table->boolean('disabled');
      $table->string('pretty_url')->unique();

      $table->double('lat');
      $table->double('lng');
      
      $table->time('checkin')->default('14:00');
      $table->time('checkout')->default('12:00');

      $table->boolean('breakfast')->default(0);
      $table->boolean('payment_by_cash')->default(true);
      $table->boolean('payment_online')->default(true);
      $table->boolean('payment_by_card')->default(true);

      $table->string('name')->unique();
      $table->string('regular_name')->unique();
      $table->string('address', 1023);

      $table->string('reception_phone')->nullable();
      $table->string('reception_email')->nullable();
      $table->string('head_phone')->nullable();
      $table->string('head_email')->nullable();
      $table->string('map')->nullable();

      $languages = collect(['ru', 'en', 'ch']);

      $languages->each(function($lang) use (&$table) {
        $table->text('reach_' . $lang)->nullable();
        $table->text('description_'. $lang)->nullable();
        $table->text('landmark_'. $lang)->nullable();
        $table->text('additional_'. $lang)->nullable();
      });

      $table->text('photos')->nullable();
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hotels');
  }
}
