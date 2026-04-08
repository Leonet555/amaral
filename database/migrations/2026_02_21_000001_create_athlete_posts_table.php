<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athlete_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('athlete_profile_id')->constrained('athlete_profiles')->cascadeOnDelete();
            $table->string('media_url');
            $table->string('media_type', 20)->default('image'); // image | video
            $table->text('caption')->nullable();
            $table->foreignId('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_posts');
    }
};
