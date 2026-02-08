<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('hotline_accesses', function (Blueprint $table) {
      $table->id();
      $table->ipAddress('ip_address')->nullable();
      $table->text('user_agent')->nullable();
      $table->string('referrer')->nullable();
      $table->string('source', 50)->default('unknown')->index();
      $table->decimal('latitude', 10, 8)->nullable();
      $table->decimal('longitude', 11, 8)->nullable();
      $table->decimal('accuracy', 10, 2)->nullable(); // meters
      $table->string('location_error')->nullable(); // if geolocation failed
      $table->timestamp('clicked_at');
      $table->timestamps();

      $table->index('clicked_at');
      $table->index('ip_address');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('hotline_accesses');
  }
};
