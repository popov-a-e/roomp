<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration {
  public function up() {
    Schema::create('promo_codes', function (Blueprint $table) {
      $table->increments('id');

      $table->unsignedInteger('user_id')->nullable();
      $table->string('code')->index()->unique()->nullable();
      $table->json('filter')->nullable();
      $table->string('discount');

      $table->date('from')->nullable();
      $table->date('till')->nullable();

      $table->boolean('is_used')->nullable();

      $table->unsignedInteger('admin_id');

      $table->timestamps();
    });
  }

  public function down() {
    Schema::dropIfExists('promo_codes');
  }
}
