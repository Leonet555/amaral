<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athlete_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('birth_date');
            $table->decimal('weight', 6, 2);
            $table->string('belt');
            $table->string('academy');
            $table->enum('gender', ['MALE', 'FEMALE']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_profiles');
    }
};
