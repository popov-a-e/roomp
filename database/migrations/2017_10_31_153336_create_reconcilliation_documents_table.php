<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReconcilliationDocumentsTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('reconcilliation_documents', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('month');
      $table->unsignedInteger('year');
      $table->unsignedInteger('hotel_id')->index();

      $table->index(['month', 'year', 'hotel_id']);

      $table->string('file')->nullable();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('reconcilliation_documents');
  }
}
