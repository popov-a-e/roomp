<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelRequisitesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('hotel_requisites', function (Blueprint $table) {
      $table->increments('id');

      $table->string('form');
      $table->string('name');
      $table->string('CEO');
      $table->string('INN');
      $table->string('OGRN');
      $table->string('OKPO');
      $table->string('account');
      $table->string('bank');
      $table->string('BIC');

      $table->string('corr_account');
      $table->text('legal_address');
      $table->text('fact_address');
      $table->text('post_address');

      $table->string('phone');
      $table->string('email');

      $table->string('contract_n');
      $table->string('contract_date');
      $table->string('contract_signee');
      $table->string('contract_scan');

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('hotel_requisites');
  }
}
