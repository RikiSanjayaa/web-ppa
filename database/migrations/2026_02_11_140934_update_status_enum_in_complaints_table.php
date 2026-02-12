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
            ->where('status', 'baru')
            ->update(['status' => 'masuk']);

        DB::table('complaints')
            ->where('status', 'diproses')
            ->update(['status' => 'diproses: LP']);

        DB::table('complaints')
            ->where('status', 'selesai')
            ->update(['status' => 'tahap 1']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'baru')
            ->update(['from_status' => 'masuk']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'baru')
            ->update(['to_status' => 'masuk']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'diproses')
            ->update(['from_status' => 'diproses: LP']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'diproses')
            ->update(['to_status' => 'diproses: LP']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'selesai')
            ->update(['from_status' => 'tahap 1']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'selesai')
            ->update(['to_status' => 'tahap 1']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('complaints')
            ->where('status', 'masuk')
            ->update(['status' => 'baru']);

        DB::table('complaints')
            ->where('status', 'diproses: LP')
            ->update(['status' => 'diproses']);

        DB::table('complaints')
            ->where('status', 'tahap 1')
            ->update(['status' => 'selesai']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'masuk')
            ->update(['from_status' => 'baru']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'masuk')
            ->update(['to_status' => 'baru']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'diproses: LP')
            ->update(['from_status' => 'diproses']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'diproses: LP')
            ->update(['to_status' => 'diproses']);

        DB::table('complaint_status_histories')
            ->where('from_status', 'tahap 1')
            ->update(['from_status' => 'selesai']);

        DB::table('complaint_status_histories')
            ->where('to_status', 'tahap 1')
            ->update(['to_status' => 'selesai']);
    }
};
