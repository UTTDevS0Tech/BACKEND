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
            Schema::create('roles', function (Blueprint $table) {
            $table->id();
            //meter el enum en vez de string 
            $table->enum('nombre', ['Administrador', 'Cliente', 'Estilista', 'Recepcionista'])->unique();
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
     Schema::dropIfExists('roles');
    }
};
