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
            Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->decimal('apartado', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2);
            $table->foreignId('personal_id')->constrained('personales')->onDelete('cascade');
            $table->time('hora_c');
            $table->date('fecha_c');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada'])->default('pendiente');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('citas');
    }
};
