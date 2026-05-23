<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rental_items')) {
            DB::statement('
                UPDATE rentals r
                SET
                    vehicle_id = ri.vehicle_id,
                    start_date = ri.start_date,
                    end_date = ri.end_date,
                    driver_id = ri.driver_id,
                    driver_fee_per_day = ri.driver_fee_per_day,
                    rental_rate_per_day = ri.rental_rate_per_day
                FROM rental_items ri
                WHERE ri.rental_id = r.id
            ');
        }

        Schema::table('rentals', function (Blueprint $table) {
            $table->foreignId('vehicle_id')->nullable(false)->change();
            $table->timestamp('start_date')->nullable(false)->change();
            $table->timestamp('end_date')->nullable(false)->change();
        });

        Schema::dropIfExists('rental_items');
    }

    public function down(): void
    {
        Schema::create('rental_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rentals')->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->restrictOnDelete();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->foreignId('driver_id')->nullable()->constrained('people')->restrictOnDelete();
            $table->integer('driver_fee_per_day')->nullable();
            $table->integer('rental_rate_per_day')->nullable();
            $table->string('status')->comment('rented, returned');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['vehicle_id', 'status', 'start_date', 'end_date']);
        });

        DB::statement('
            INSERT INTO rental_items (rental_id, vehicle_id, start_date, end_date, driver_id, driver_fee_per_day, rental_rate_per_day, status, created_at, updated_at)
            SELECT id, vehicle_id, start_date, end_date, driver_id, driver_fee_per_day, rental_rate_per_day, \'rented\', now(), now()
            FROM rentals
            WHERE vehicle_id IS NOT NULL
        ');

        Schema::table('rentals', function (Blueprint $table) {
            $table->timestamp('start_date')->nullable()->change();
            $table->timestamp('end_date')->nullable()->change();
            $table->foreignId('vehicle_id')->nullable()->change();
        });
    }
};
