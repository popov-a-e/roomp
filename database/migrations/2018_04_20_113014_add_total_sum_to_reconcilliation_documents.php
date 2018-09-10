<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalSumToReconcilliationDocuments extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('reconcilliation_documents', function(Blueprint $table) {
      $table->unsignedInteger('total')->default(0);
      $table->unsignedInteger('agent_total')->default(0);
      $table->unsignedInteger('hotel_total')->default(0);
      $table->unsignedInteger('credit')->default(0);

      $table->boolean('paid')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('reconcilliation_documents', function(Blueprint $table) {
      $table->dropColumn('total','agent_total', 'hotel_total', 'credit', 'paid');
    });
  }
}
