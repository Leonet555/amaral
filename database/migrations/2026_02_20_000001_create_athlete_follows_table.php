<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athlete_follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('follower_athlete_id')->constrained('athlete_profiles')->cascadeOnDelete();
            $table->foreignId('following_athlete_id')->constrained('athlete_profiles')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['follower_athlete_id', 'following_athlete_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_follows');
    }
};
