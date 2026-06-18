<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('baby_names', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // meisjes | jongens | tweeling
            $table->timestamps();

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baby_names');
    }
};
