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
        Schema::table('steer_listings', function (Blueprint $table) {
            $table->enum('status', ['draft', 'active', 'cancelled'])->default('draft')->after('price');
            $table->string('stripe_subscription_id')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('steer_listings', function (Blueprint $table) {
            $table->dropColumn(['status', 'stripe_subscription_id']);
        });
    }
};
