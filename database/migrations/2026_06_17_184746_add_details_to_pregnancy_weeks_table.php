<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pregnancy_weeks', function (Blueprint $table) {
            $table->text('course_tip')->nullable()->after('tip'); // Tips zwangerschapscursus
            $table->text('warning')->nullable()->after('course_tip'); // Waarschuwing
            $table->text('nursery')->nullable()->after('warning'); // Babykamer
        });
    }

    public function down(): void
    {
        Schema::table('pregnancy_weeks', function (Blueprint $table) {
            $table->dropColumn(['course_tip', 'warning', 'nursery']);
        });
    }
};
