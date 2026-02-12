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
        Schema::table('complaints', function (Blueprint $table) {
            $table->string('testimonial_token', 64)->nullable()->unique()->index()->after('id');
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->string('testimonial_token', 64)->nullable()->unique()->index()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('testimonial_token');
        });

        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn('testimonial_token');
        });
    }
};
