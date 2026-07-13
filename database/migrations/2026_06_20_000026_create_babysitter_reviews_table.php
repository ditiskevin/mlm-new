<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('babysitter_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('babysitter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->unsignedTinyInteger('rating');           // 1..5 sterren
            $table->text('body')->nullable();
            $table->timestamps();

            // One review per member per sitter (editable via updateOrCreate).
            $table->unique(['babysitter_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('babysitter_reviews');
    }
};
