<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfferConfirmationToHotelier extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table('hoteliers', function(Blueprint $table) {
      $table->string('name')->nullable()->change();
      $table->string('email')->nullable()->change();
      $table->string('phone')->nullable()->change();
      $table->string('password')->nullable()->change();
      $table->string('organization')->nullable()->change();

      $table->string('registration_token')->nullable();
      $table->string('old_email')->nullable();
      $table->string('old_password')->nullable();
    });

    Schema::table('hotels', function(Blueprint $table) {
      $table->dropColumn('hotelier_registration_token');
      $table->dropColumn('email_confirmed');
      $table->dropColumn('password_confirmed');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table('hoteliers', function(Blueprint $table) {
      $table->dropColumn(
        'old_email',
        'old_password'
        );
    });

    Schema::table('hotels', function(Blueprint $table) {
      $table->string('hotelier_registration_token')->nullable();
      $table->string('email_confirmed')->default('1');
      $table->string('password_confirmed')->default('1');
    });
  }
}
