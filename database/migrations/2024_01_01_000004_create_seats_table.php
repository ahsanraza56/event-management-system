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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('seat_number'); // e.g., "A1", "B5", "VIP1"
            $table->string('row'); // e.g., "A", "B", "VIP"
            $table->string('section')->default('main'); // e.g., "main", "balcony", "vip"
            $table->enum('status', ['available', 'booked', 'reserved'])->default('available');
            $table->decimal('price', 8, 2)->nullable(); // Individual seat price (can override event price)
            $table->timestamps();
            
            // Ensure unique seats per event
            $table->unique(['event_id', 'seat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
}; 