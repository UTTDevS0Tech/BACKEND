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
            Schema::create('tipo_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->double('precio', 10, 2);
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('imagen');
            $table->integer('tiempo_estimado');
            $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_servicios');

    }
};
