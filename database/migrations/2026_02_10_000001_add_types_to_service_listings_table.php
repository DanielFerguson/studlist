<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('service_listings')) {
            return;
        }

        if (! Schema::hasColumn('service_listings', 'types')) {
            Schema::table('service_listings', function (Blueprint $table) {
                $table->json('types')->nullable()->after('type');
            });
        }

        // Backfill `types` from legacy single `type` column.
        DB::table('service_listings')
            ->select(['id', 'type', 'types'])
            ->orderBy('id')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    $existing = $row->types;
                    $decoded = is_string($existing) ? json_decode($existing, true) : $existing;

                    if (is_array($decoded) && count($decoded) > 0) {
                        continue;
                    }

                    if (! is_string($row->type) || $row->type === '') {
                        continue;
                    }

                    DB::table('service_listings')
                        ->where('id', $row->id)
                        ->update(['types' => json_encode([$row->type])]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('service_listings')) {
            return;
        }

        if (Schema::hasColumn('service_listings', 'types')) {
            Schema::table('service_listings', function (Blueprint $table) {
                $table->dropColumn('types');
            });
        }
    }
};

