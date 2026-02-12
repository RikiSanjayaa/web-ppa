<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('complaints')
            ->where('status', 'tahap 1')
            ->update(['status' => 'Selesai Tahap 1']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'tahap 1')
            ->update(['from_status' => 'Selesai Tahap 1']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'tahap 1')
            ->update(['to_status' => 'Selesai Tahap 1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('complaints')
            ->where('status', 'Selesai Tahap 1')
            ->update(['status' => 'tahap 1']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'Selesai Tahap 1')
            ->update(['from_status' => 'tahap 1']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'Selesai Tahap 1')
            ->update(['to_status' => 'tahap 1']);
    }
};
