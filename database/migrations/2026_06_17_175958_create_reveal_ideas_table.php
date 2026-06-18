<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reveal_ideas', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // partner | familie | vrienden | gender | kaartje
            $table->string('title');
            $table->text('description');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reveal_ideas');
    }
};
