<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amount');
            $table->string('category');
            $table->string('description')->nullable();
            $table->date('date');
            $table->string('proof_file_path')->nullable();
            $table->foreignId('person_id')->nullable()->constrained('people');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
