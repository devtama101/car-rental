<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable()->after('user_id')
                ->constrained('vehicles')->restrictOnDelete();
            $table->timestamp('start_date')->nullable()->after('vehicle_id');
            $table->timestamp('end_date')->nullable()->after('start_date');
            $table->foreignId('driver_id')->nullable()->after('end_date')
                ->constrained('people')->restrictOnDelete();
            $table->integer('driver_fee_per_day')->nullable()->after('driver_id');
            $table->integer('rental_rate_per_day')->nullable()->after('driver_fee_per_day');
        });
    }

    public function down(): void
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dropForeign(['vehicle_id']);
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['vehicle_id', 'start_date', 'end_date', 'driver_id', 'driver_fee_per_day', 'rental_rate_per_day']);
        });
    }
};
