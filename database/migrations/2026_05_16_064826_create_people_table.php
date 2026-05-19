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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('restrict');
            $table->string('type')->comment('customer, employee, admin');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('id_type')->nullable()->comment('ktp, sim, paspor, kartu pelajar');
            $table->string('id_file_path')->nullable()->comment('Lokasi file ID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
