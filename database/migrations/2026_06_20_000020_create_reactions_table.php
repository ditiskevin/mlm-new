<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable'); // reactable_type + reactable_id
            $table->string('emoji');
            $table->timestamps();

            // One reaction per user per item; changing the emoji replaces it.
            $table->unique(['user_id', 'reactable_type', 'reactable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
