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

        $driver = Schema::getConnection()->getDriverName();

        // Drop any enum/check constraints by ensuring the column is a plain varchar.
        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE service_listings MODIFY type VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            // Laravel's `enum()` compiles to a CHECK constraint on Postgres.
            DB::statement('ALTER TABLE service_listings DROP CONSTRAINT IF EXISTS service_listings_type_check');
            DB::statement('ALTER TABLE service_listings ALTER COLUMN type TYPE VARCHAR(255)');
        } else {
            // SQLite doesn't support altering a column in-place; Laravel rebuilds the table for `change()`.
            Schema::table('service_listings', function (Blueprint $table) {
                $table->string('type')->change();
            });
        }

        // Normalize legacy values to the current UI/service taxonomy.
        DB::table('service_listings')->where('type', 'Photographer')->update(['type' => 'Photography']);
        DB::table('service_listings')->where('type', 'Fitter')->update(['type' => 'Fitting']);
        DB::table('service_listings')->where('type', 'Feeder')->update(['type' => 'Feed Supplier']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('service_listings')) {
            return;
        }

        // Map current values back to the original enum options.
        DB::table('service_listings')->where('type', 'Photography')->update(['type' => 'Photographer']);
        DB::table('service_listings')->where('type', 'Fitting')->update(['type' => 'Fitter']);
        DB::table('service_listings')->where('type', 'Feed Supplier')->update(['type' => 'Feeder']);

        DB::table('service_listings')
            ->whereIn('type', ['Clipping', 'Transport', 'Veterinary', 'Show Preparation'])
            ->update(['type' => 'Other']);

        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE service_listings MODIFY type ENUM('Photographer', 'Fitter', 'Feeder', 'Other') NOT NULL");
        } elseif ($driver === 'pgsql') {
            // Restore the original CHECK constraint shape.
            DB::statement('ALTER TABLE service_listings DROP CONSTRAINT IF EXISTS service_listings_type_check');
            DB::statement(
                "ALTER TABLE service_listings ADD CONSTRAINT service_listings_type_check CHECK (type IN ('Photographer', 'Fitter', 'Feeder', 'Other'))"
            );
        } else {
            Schema::table('service_listings', function (Blueprint $table) {
                $table->enum('type', ['Photographer', 'Fitter', 'Feeder', 'Other'])->change();
            });
        }
    }
};

