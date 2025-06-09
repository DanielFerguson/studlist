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
        Schema::create('steer_listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('breed');
            $table->string('colour');
            $table->string('location');
            $table->string('sire')->nullable();
            $table->string('dam')->nullable();
            $table->string('business_contact')->nullable();
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->float('price')->nullable();
            $table->string('pic_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('started_on_feed')->default(false);
            $table->jsonb('photos')->default('[]');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('steer_listings');
    }
};
