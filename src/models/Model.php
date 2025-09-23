<?php

namespace App\Models;

use App\Core\Database;
use PDO;

/**
 * Clase base abstracta para todos los modelos.
 * Proporciona la funcionalidad básica del ORM (CRUD).
 */
abstract class Model
{
    protected Database $db;
    protected string $table; // Cada modelo hijo debe definir su tabla
    protected string $primaryKey = 'id'; // Clave primaria por defecto

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Encuentra todos los registros de la tabla.
     * @return array
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Encuentra un registro por su clave primaria.
     * @param mixed $id
     * @return array|false
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Elimina un registro por su clave primaria.
     * @param mixed $id
     * @return bool
     */
    public function delete($id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Crea un nuevo registro en la base de datos.
     *
     * @param array $data Un array asociativo de [columna => valor].
     * @return bool True si la inserción fue exitosa, false en caso contrario.
     */
    public function create(array $data): bool
    {
        // Obtiene los nombres de las columnas del array
        $columns = array_keys($data);
        $columnSql = implode(', ', $columns);

        // Crea los placeholders (?) para los valores
        $placeholderSql = implode(', ', array_fill(0, count($columns), '?'));

        // Obtiene los valores que se van a insertar
        $values = array_values($data);

        // Construye la consulta SQL final
        $sql = "INSERT INTO {$this->table} ({$columnSql}) VALUES ({$placeholderSql})";

        // Ejecuta la consulta de forma segura
        $stmt = $this->db->query($sql, $values);

        // Devuelve true si se insertó al menos una fila
        return $stmt->rowCount() > 0;
    }

    
}