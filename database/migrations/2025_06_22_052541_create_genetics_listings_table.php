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
        Schema::create('genetics_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->json('photos')->nullable();
            $table->string('breed');
            $table->enum('type', ['Semen Straws', 'Embryos']);
            $table->string('sire')->nullable();
            $table->string('dam')->nullable();
            $table->string('registration_link')->nullable();
            $table->enum('storage_location', ['ACT', 'NSW', 'NT', 'QLD', 'SA', 'TAS', 'VIC', 'WA']);
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genetics_listings');
    }
};