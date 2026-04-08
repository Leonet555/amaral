<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('location');
            $table->enum('sport_type', ['BJJ', 'JUDO']);
            $table->dateTime('registration_deadline');
            $table->enum('status', ['DRAFT', 'OPEN', 'CLOSED', 'STARTED', 'FINISHED'])->default('DRAFT');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
