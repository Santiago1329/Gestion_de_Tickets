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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('archivo_adjunto')->nullable();
            $table->string('telefono')->nullable();
            $table->enum('estado', ['abierto', 'en_proceso', 'resuelto', 'cancelado', 're_abierto'])->default('abierto');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->default('media');
            // LLaves foraneas para relacionar con categorias y usuarios
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict'); // Si una categoria es eliminada, no se eliminarán los tickets asociados
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Si un usuario es eliminado, sus tickets también lo serán
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
