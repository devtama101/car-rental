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
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('rental_id')
                ->constrained()
                ->onDelete('restrict');
            $table->foreignId('vehicle_id')
                ->constrained()
                ->onDelete('restrict');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->foreignId('driver_id')
                ->nullable()
                ->constrained('people')
                ->onDelete('restrict');
            $table->integer('driver_fee_per_day')->nullable();
            $table->integer('rental_rate_per_day')->nullable();
            $table->string('status')->comment('rented, returned');

            $table->index(['vehicle_id', 'status', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};
