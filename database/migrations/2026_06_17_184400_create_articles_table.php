<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('author_name')->default('Stephanie van der Kooij');
            $table->string('color_from')->default('#FCE7EB');
            $table->string('color_to')->default('#EAF5EE');
            $table->string('emoji')->default('💛');
            $table->unsignedSmallInteger('reading_minutes')->default(4);
            $table->text('excerpt');
            $table->longText('body'); // paragraphs separated by blank lines
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
