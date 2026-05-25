<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('rental_type');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->boolean('requires_driver')->default(false)->after('transmission');
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('requires_driver');
        });

        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('rental_type')->default('both')->after('license_plate');
        });
    }
};
