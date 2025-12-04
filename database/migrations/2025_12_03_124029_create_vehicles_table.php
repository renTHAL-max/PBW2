<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_category_id')->constrained()->onDelete('cascade');
            $table->string('license_plate')->unique();
            $table->string('brand');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->decimal('price_per_day', 12, 2);
            $table->enum('status', ['tersedia', 'sedang_disewa', 'dalam_perawatan'])->default('tersedia');
            $table->text('description')->nullable();
            $table->integer('seat_capacity')->nullable();
            $table->string('transmission')->nullable(); // manual/automatic
            $table->string('fuel_type')->nullable(); // bensin/diesel/electric
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};