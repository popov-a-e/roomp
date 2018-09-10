<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('reservations', function (Blueprint $table) {
      $table->increments('id');
      $table->char('code',8);
      $table->string('token');
      $table->string('cancel_token');

      $table->unsignedInteger('hotel_id')->index();

      $table->date('from');
      $table->date('till');

      $table->string('guest_name');
      $table->text('comment')->nullable();

      $table->unsignedInteger('user_id')->nullable()->index();
      $table->unsignedInteger('channel_id')->index();

      $table->timestamps();

      $table->index(['from', 'till']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('reservations');
  }
}
