<?php

namespace App\Database\Seeders;

use App\Database\Factories\UsuarioFactory;

// Importa aquí las factories que necesites para este seeder.
// Ejemplo: use App\Database\Factories\UserFactory;

class UsuarioSeeder
{
    /**
     * Ejecuta los seeds para esta entidad.
     *
     * @return void
     */
    public function run(): void
    {
        // Aquí es donde llamas a la factory correspondiente.
        // Por ejemplo, para crear 50 usuarios:
        //
        (new UsuarioFactory())->count(50)->create();
        echo " - 50 usuarios creados.\n";
    }
}
