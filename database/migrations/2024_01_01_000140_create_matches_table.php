<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('athlete_1_id')->constrained('athlete_profiles');
            $table->foreignId('athlete_2_id')->nullable()->constrained('athlete_profiles');
            $table->foreignId('winner_id')->nullable()->constrained('athlete_profiles');
            $table->unsignedInteger('round_number');
            $table->unsignedInteger('match_number');
            $table->enum('status', ['PENDING', 'FINISHED'])->default('PENDING');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
