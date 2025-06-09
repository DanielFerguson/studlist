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
        // First, update any existing 'paused' records to 'cancelled'
        DB::table('steer_listings')
            ->where('status', 'paused')
            ->update(['status' => 'cancelled']);

        // SQLite doesn't support modifying enum columns directly
        // The constraint will be enforced at the application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No database changes needed for SQLite - this was application-level only
    }
};
