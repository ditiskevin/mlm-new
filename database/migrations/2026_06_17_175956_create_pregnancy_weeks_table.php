<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pregnancy_weeks', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('position');   // 0-based order in the calendar
            $table->string('label');                     // e.g. "1–2", "14"
            $table->unsignedTinyInteger('trimester');    // 1, 2 or 3
            $table->string('fruit');
            $table->string('emoji');
            $table->string('size');
            $table->text('baby');
            $table->text('mom');
            $table->text('tip');
            $table->text('milestone')->nullable();
            $table->text('echo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pregnancy_weeks');
    }
};
