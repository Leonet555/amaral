<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('belt');
            $table->decimal('weight_min', 6, 2);
            $table->decimal('weight_max', 6, 2);
            $table->unsignedInteger('age_min');
            $table->unsignedInteger('age_max');
            $table->enum('gender', ['MALE', 'FEMALE', 'MIXED']);
            $table->unsignedInteger('max_participants');
            $table->boolean('bracket_generated')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
