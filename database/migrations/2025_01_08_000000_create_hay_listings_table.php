<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hay_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic Information
            $table->string('title');
            $table->enum('hay_type', ['Lucerne', 'Grass', 'Oaten', 'Meadow', 'Clover', 'Mixed', 'Wheaten', 'Barley', 'Sorghum', 'Other']);
            $table->enum('bale_type', ['Round', 'Small Square', 'Large Square']);
            $table->integer('quantity');
            $table->decimal('weight_per_bale', 8, 2)->nullable();
            $table->enum('season_cut', ['1st Cut', '2nd Cut', '3rd Cut', '4th Cut', 'Multiple Cuts'])->nullable();
            $table->year('cut_year')->nullable();

            // Quality & Testing
            $table->enum('quality_grade', ['Premium', 'A-Grade', 'B-Grade', 'C-Grade', 'Stockfeed'])->nullable();
            $table->boolean('test_results_available')->default(false);
            $table->decimal('protein_percentage', 5, 2)->nullable();
            $table->decimal('moisture_percentage', 5, 2)->nullable();
            $table->decimal('energy_mj_kg', 5, 2)->nullable();
            $table->enum('nitrate_level', ['Low', 'Medium', 'High'])->nullable();
            $table->boolean('weather_damaged')->default(false);

            // Storage & Location
            $table->enum('storage_type', ['Shed', 'Covered Outdoor', 'Open Paddock']);
            $table->string('location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('delivery_available')->default(false);
            $table->integer('delivery_radius_km')->nullable();
            $table->integer('minimum_order_quantity')->nullable();

            // Pricing
            $table->enum('price_type', ['Per Bale', 'Per Tonne', 'Negotiable']);
            $table->decimal('price_per_bale', 10, 2)->nullable();
            $table->decimal('price_per_tonne', 10, 2)->nullable();

            // Contact
            $table->string('business_contact')->nullable();
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->string('pic_number')->nullable();

            // Media & Description
            $table->json('photos')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('hay_type');
            $table->index('bale_type');
            $table->index('quality_grade');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hay_listings');
    }
};
