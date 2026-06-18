<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();
            $table->string('type');     // uitzet | vluchttas
            $table->string('category'); // e.g. Kleding, Documenten
            $table->string('label');
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();

            $table->index(['type', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
