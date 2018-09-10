<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TranslateSecondaryTables extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */

  private $tables;

  public function __construct() {
    $this->tables = collect(['room_amenities', 'hotel_amenities', 'allotments', 'cities', 'room_classes']);
  }

  public function up() {
    $this->tables->each(function($t) {
      Schema::table($t, function(Blueprint $table) {
        $table->renameColumn('name','ru');
        $table->string('en')->nullable();
        $table->string('ch')->nullable();
      });
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    $this->tables->each(function($t) {
      Schema::table($t, function(Blueprint $table) {
        $table->renameColumn('ru', 'name');
        $table->dropColumn('en');
        $table->dropColumn('ch');
      });
    });
  }
}
