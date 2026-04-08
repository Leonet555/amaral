<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_id')->constrained('athlete_profiles')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('payment_status', ['PENDING', 'PAID'])->default('PENDING');
            $table->timestamps();

            $table->unique(['athlete_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
