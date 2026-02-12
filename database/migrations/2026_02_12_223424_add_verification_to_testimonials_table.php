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
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('name');
            $table->boolean('is_verified')->default(false)->after('phone_number')->index();
            $table->foreignId('consultation_id')->nullable()->after('is_verified')->constrained('consultations')->nullOnDelete();
            $table->foreignId('complaint_id')->nullable()->after('consultation_id')->constrained('complaints')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            //
        });
    }
};
