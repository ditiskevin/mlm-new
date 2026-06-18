<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audiences', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('emoji')->default('💛');
            $table->string('tagline');
            $table->text('intro');
            $table->longText('body'); // paragraphs separated by blank lines
            $table->json('tips')->nullable();
            $table->string('color_from')->default('#FCE7EB');
            $table->string('color_to')->default('#EAF5EE');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audiences');
    }
};
