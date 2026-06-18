<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');                       // Kleding, Speelgoed, ...
            $table->string('offer_type')->default('aanbod');  // aanbod | ruil | gratis | gevraagd
            $table->decimal('price', 8, 2)->nullable();
            $table->string('condition')->nullable();          // nieuw | zo goed als nieuw | gebruikt
            $table->string('location');
            $table->text('description');
            $table->string('author_name');
            $table->string('avatar_color')->default('#F7A8B5');
            $table->string('emoji')->default('📦');
            $table->timestamps();

            $table->index(['category', 'offer_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
