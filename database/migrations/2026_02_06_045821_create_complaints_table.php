<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp');
            $table->string('email')->nullable();
            $table->string('tempat_kejadian');
            $table->dateTime('waktu_kejadian');
            $table->text('kronologis_singkat');
            $table->string('korban')->nullable();
            $table->string('terlapor')->nullable();
            $table->text('saksi_saksi')->nullable();
            $table->string('status')->default('baru')->index();
            $table->string('channel')->default('web');
            $table->timestamp('wa_redirected_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->index(['created_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
