<?php

use App\Database\Schema;
use App\Database\Blueprint;

/**
 * Migración para la tabla usuarios.
 * Generada el: 2025_12_06_101558
 */
return new class
{
    /**
     * Ejecuta la migración para construir el esquema.
     * Aquí es donde defines la estructura de tu tabla.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('dni');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('pass');
            $table->integer('rol');

            $table->timestamps();
        });
    }

    /**
     * Revierte la migración.
     * Generalmente, esto implica eliminar la tabla.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
