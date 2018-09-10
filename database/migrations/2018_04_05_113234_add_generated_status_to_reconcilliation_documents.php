<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeneratedStatusToReconcilliationDocuments extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('reconcilliation_documents', function(Blueprint $table) {
      $table->boolean('generated')->default(false);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('reconcilliation_documents', function(Blueprint $table) {
      $table->dropColumn('generated');
    });
  }
}
