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
    protected array $attributes = []; // Almacena los datos de la fila


    // --- Propiedades para el Query Builder ---
    protected array $queryParts = [
        'select' => '*',
        'where' => [],
        'orderBy' => '',
        'limit' => '',
        'params' => []
    ];


    public function __construct(array $attributes = [])
    {
        $this->db = Database::getInstance();
        $this->fill($attributes);
    }

    /**
     * Llena el modelo con un array de atributos.
     */
    public function fill(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    // ===================================================================
    //                          ACTIVE RECORD
    // ===================================================================


    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Guarda el modelo en la base de datos (crea si no tiene ID, actualiza si lo tiene).
     * @return bool
     */
    public function save(): bool
    {
        if (isset($this->attributes[$this->primaryKey])) {
            return $this->performUpdate();
        } else {
            return $this->performCreate();
        }
    }

    /**
     * Encuentra un registro por su clave primaria y devuelve una instancia del modelo.
     * @return static|null Una instancia del modelo o null si no se encuentra.
     */
    public static function find($id)
    {
        $model = new static();
        $sql = "SELECT * FROM {$model->table} WHERE {$model->primaryKey} = ?";
        $stmt = $model->db->query($sql, [$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $model->fill($data);
            return $model;
        }
        return null;
    }

    /**
     * Elimina el registro actual de la base de datos.
     * @return bool
     */
    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->query($sql, [$this->attributes[$this->primaryKey]]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Crea un nuevo registro usando los atributos del objeto.
     */
    protected function performCreate(): bool
    {
        $columns = array_keys($this->attributes);
        $values = array_values($this->attributes);
        
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(', ', $columns),
            implode(', ', array_fill(0, count($values), '?'))
        );

        $stmt = $this->db->query($sql, $values);

        if ($stmt->rowCount() > 0) {
            // Si la clave primaria no es autoincremental, ya está en los atributos.
            // Si es autoincremental, la obtenemos.
            if (!isset($this->attributes[$this->primaryKey])) {
                $this->attributes[$this->primaryKey] = $this->db->getConnection()->lastInsertId();
            }
            return true;
        }
        return false;
    }

    /**
     * Actualiza el registro existente usando los atributos del objeto.
     */
    protected function performUpdate(): bool
    {
        $setClauses = [];
        $values = [];
        foreach ($this->attributes as $column => $value) {
            if ($column !== $this->primaryKey) {
                $setClauses[] = "{$column} = ?";
                $values[] = $value;
            }
        }
        $values[] = $this->attributes[$this->primaryKey];

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s = ?',
            $this->table,
            implode(', ', $setClauses),
            $this->primaryKey
        );
        
        $stmt = $this->db->query($sql, $values);
        return $stmt->rowCount() > 0;
    }

    // ===================================================================
    //                        QUERY BUILDER
    // ===================================================================

    /**
     * Añade una condición WHERE a la consulta.
     * @return $this
     */
    public function where(string $column, string $operator, $value): self
    {
        $this->queryParts['where'][] = "{$column} {$operator} ?";
        $this->queryParts['params'][] = $value;
        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->queryParts['orderBy'] = "ORDER BY {$column} " . strtoupper($direction);
        return $this;
    }

    public function limit(int $count): self
    {
        $this->queryParts['limit'] = "LIMIT {$count}";
        return $this;
    }

    /**
     * Ejecuta la consulta construida y devuelve los resultados.
     * Puede devolver un array de arrays o un array de objetos del modelo.
     * @param bool $asModel Si es true, devuelve instancias del modelo.
     * @return array
     */
    public function get(bool $asModel = false): array
    {
        $sql = "SELECT {$this->queryParts['select']} FROM {$this->table}";

        if (!empty($this->queryParts['where'])) {
            $sql .= " WHERE " . implode(' AND ', $this->queryParts['where']);
        }
        if ($this->queryParts['orderBy']) $sql .= " " . $this->queryParts['orderBy'];
        if ($this->queryParts['limit']) $sql .= " " . $this->queryParts['limit'];
        
        $stmt = $this->db->query($sql, $this->queryParts['params']);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->resetQuery();

        if ($asModel) {
            $collection = [];
            foreach ($results as $row) {
                $collection[] = new static($row);
            }
            return $collection;
        }

        return $results;
    }

    private function resetQuery(): void
    {
        $this->queryParts = ['select' => '*', 'where' => [], 'orderBy' => '', 'limit' => '', 'params' => []];
    }

}
