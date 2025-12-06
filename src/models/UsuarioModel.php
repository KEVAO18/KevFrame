<?php

namespace App\Models;

class UsuarioModel extends Model
{
    /**
     * El nombre de la tabla en la base de datos.
     */
    protected string $table = 'usuarios';

    /**
     * La clave primaria de la tabla.
     */
    protected string $primaryKey = 'dni';

    /**
     * El esquema de la tabla (descubierto automáticamente).
     */
    protected array $fields = [
        'dni' => 'int(11)',
        'nombre' => 'varchar(255)',
        'apellido' => 'varchar(255)',
        'email' => 'varchar(255)',
        'pass' => 'varchar(255)',
        'rol' => 'int(11)',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * Define las relaciones del modelo aquí.
     */
    protected array $relations = [];
}
