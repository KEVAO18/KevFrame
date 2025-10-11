<?php

namespace App\Database;

use App\Core\Database;
use Closure;


class Schema {


    /**
     * Crea una nueva tabla en la base de datos.
     *
     * @param string $tableName El nombre de la tabla a crear.
     * @param Closure $callback Una función que recibe un objeto Blueprint para definir las columnas.
     */
    public static function create(string $tableName, Closure $callback): void
    {
        $blueprint = new Blueprint($tableName);
        $callback($blueprint); // El usuario define la estructura de la tabla en el Blueprint.

        // El Blueprint genera la sentencia SQL correspondiente.
        $sql = $blueprint->toSql();

        // Se ejecuta la consulta para crear la tabla.
        $db = Database::getInstance();
        $db->query($sql);

        echo "Tabla '{$tableName}' creada exitosamente.\n";
    }

    /**
     * Elimina una tabla si existe.
     *
     * @param string $tableName El nombre de la tabla a eliminar.
     */
    public static function dropIfExists(string $tableName): void
    {
        $sql = "DROP TABLE IF EXISTS `{$tableName}`";
        $db = Database::getInstance();
        $db->query($sql);
        echo "Tabla '{$tableName}' eliminada.\n";
    }
}