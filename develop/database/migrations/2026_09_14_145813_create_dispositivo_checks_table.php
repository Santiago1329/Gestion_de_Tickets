<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispositivo_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispositivo_id')->constrained()->cascadeOnDelete();
            $table->enum('estado', ['online', 'offline']);
            $table->timestamp('checked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivo_checks');
    }
};
