<?php

namespace App\Database\Seeders;

// Importa aquí todos los seeders individuales que vayas a ejecutar.
// Ejemplo: use App\Database\Seeders\UserSeeder;
// Ejemplo: use App\Database\Seeders\PostSeeder;

class Seeder
{
    /**
     * Ejecuta todos los seeders de la aplicación.
     * Este es el punto de entrada para el comando 'db:seed'.
     */
    public function run(): void
    {
        echo "Iniciando el poblado de la base de datos...\n";

        // Llama a otros seeders en el orden que necesites.
        // La relación es importante: crea usuarios antes de crear posts que les pertenezcan.
        $this->call([
            UsuarioSeeder::class,
        ]);

        echo "\nPoblado de la base de datos completado exitosamente.\n";
    }

    /**
     * Método de ayuda para ejecutar un array de clases seeder.
     *
     * @param array $seeders
     * @return void
     */
    protected function call(array $seeders): void
    {
        foreach ($seeders as $seederClass) {
            echo "\nEjecutando seeder: {$seederClass}\n";
            $seeder = new $seederClass();
            $seeder->run();
        }
    }
}
