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
        Schema::create('stud_listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->date('date_of_birth');
            $table->string('breed');
            $table->string('colour');
            $table->string('tattoo_number')->nullable();
            $table->string('location');
            $table->string('sire')->nullable();
            $table->string('dam')->nullable();
            $table->string('registration_link')->nullable();
            $table->string('business_contact')->nullable();
            $table->string('phone_contact')->nullable();
            $table->string('email_contact')->nullable();
            $table->string('pic_number')->nullable();
            $table->text('description')->nullable();
            $table->jsonb('photos')->default('[]');
            $table->enum('status', ['draft', 'active', 'cancelled'])->default('draft');
            $table->string('stripe_subscription_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stud_listings');
    }
};
