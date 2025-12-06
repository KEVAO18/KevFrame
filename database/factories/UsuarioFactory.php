<?php

namespace App\Database\Factories;

use App\Models\UsuarioModel;
use App\Database\Factories\Factory; // Añadir esto
use Faker\Factory as Faker;

class UsuarioFactory extends Factory
{
    protected string $model = UsuarioModel::class;

    /**
     * Define la estructura de datos por defecto para el modelo.
     */
    public function definition(): array
    {

        $faker = Faker::create('es_ES');

        return [
            'dni' => $faker->unique()->numberBetween(1000000, 99999999),
            'nombre' => $faker->firstName(),
            'apellido' => $faker->lastName(),
            'email' => $faker->unique()->safeEmail(),
            'pass' => $faker->password(),
            'rol' => $faker->numberBetween(1, 3)
        ];
    }
}
