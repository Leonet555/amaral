<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('athlete_profiles', function (Blueprint $table) {
            $table->string('photo_url', 500)->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('athlete_profiles', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
    }
};
