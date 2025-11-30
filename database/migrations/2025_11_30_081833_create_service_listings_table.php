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
        Schema::create('service_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['Photographer', 'Fitter', 'Feeder', 'Other']);
            $table->string('abn')->nullable();
            $table->string('business_name');
            $table->string('contact_name');
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->json('locations_covered'); // Array of states: ACT, NSW, NT, QLD, SA, TAS, VIC, WA
            $table->json('links')->nullable(); // Array of website/social links
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_listings');
    }
};
