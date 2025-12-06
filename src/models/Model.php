<?php

namespace App\Models;

use App\Core\Database;
use App\Database\Relations\BelongsTo;
use App\Database\Relations\HasMany;
use App\Database\Relations\HasOne;
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
    protected bool $exists = false; // Indica si el modelo existe en la base de datos


    // --- Propiedades para el Query Builder ---
    protected array $queryParts = [
        'select' => '*',
        'where' => [],
        'orderBy' => '',
        'limit' => '',
        'params' => []
    ];


    /**
     * Constructor del modelo.
     * @param array $attributes Array de atributos para inicializar el modelo.
     */
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


    /**
     * Acceso a propiedades dinámicas.
     * Si el atributo existe, lo devuelve. Si es una relación, la carga y la devuelve.
     */
    public function __get($key)
    {
        // Si el atributo ya existe, lo retornamos
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        // Si no, verificamos si es una relación (un método con el mismo nombre)
        if (method_exists($this, $key)) {
            // Llamamos al método de la relación para obtener los datos
            $relation = $this->{$key}();
            $relatedModel = $relation->getResults();

            // Guardamos el resultado para no tener que volver a cargarlo
            $this->attributes[$key] = $relatedModel;

            return $relatedModel;
        }

        return null;
    }

    /**
     * Asignación dinámica de propiedades.
     * Si la propiedad es una relación, la guarda en el array de atributos.
     */
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
        if ($this->exists) {
            return $this->performUpdate();
        } else {
            return $this->performCreate();
        }
    }

    /**
     * Encuentra un registro por su clave primaria y devuelve una instancia del modelo.
     * @return static|null Una instancia del modelo o null si no se encuentra.
     */
    public static function find($id): ?static
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
            $this->exists = true;
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
     * Añade una cláusula SELECT a la consulta.
     * @return $this
     */
    public function select(string $columns = "*"): self
    {
        $this->queryParts['select'] = $columns;
        return $this;
    }

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

    /**
     * Añade un ORDER BY a la consulta.
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->queryParts['orderBy'] = "ORDER BY {$column} " . strtoupper($direction);
        return $this;
    }

    /**
     * Añade un LIMIT a la consulta.
     * @return $this
     */
    public function limit(int $count): self
    {
        $this->queryParts['limit'] = "LIMIT {$count}";
        return $this;
    }

    /**
     * Añade un offset a la consulta.
     * @return $this
     */
    public function offset(int $count): self
    {
        $this->queryParts['offset'] = "OFFSET {$count}";
        return $this;
    }

    /**
     * Añade un GROUP BY a la consulta.
     * @return $this
     */
    public function groupBy(string $column): self
    {
        $this->queryParts['groupBy'] = "GROUP BY {$column}";
        return $this;
    }

    /**
     * Añade una condición HAVING a la consulta.
     * @return $this
     */
    public function having(string $column, string $operator, $value): self
    {
        $this->queryParts['having'][] = "{$column} {$operator} ?";
        $this->queryParts['params'][] = $value;
        return $this;
    }

    /**
     * Añade una cláusula JOIN a la consulta.
     * @return $this
     */
    public function join(string $table, string $condition): self
    {
        $this->queryParts['join'][] = "JOIN {$table} ON {$condition}";
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

        if (!empty($this->queryParts['join'])) {
            $sql .= " " . implode(' ', $this->queryParts['join']);
        }

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

    // ===================================================================
    //                        RELATIONS
    // ===================================================================

    /**
     * Define una relación "pertenece a" (inversa de uno a muchos).
     *
     * @param string $relatedModel La clase del modelo relacionado.
     * @param string $foreignKey La clave foránea en la tabla actual.
     * @param string $ownerKey La clave primaria en la tabla relacionada.
     * @return BelongsTo
     */
    protected function belongsTo(string $relatedModel, string $foreignKey, string $ownerKey = 'id')
    {
        // Creamos una instancia de la clase de la relación
        $relation = new BelongsTo($relatedModel, $foreignKey, $ownerKey);
        $relation->setParent($this); // Le pasamos el modelo actual a la relación
        return $relation;
    }

    /**
     * Define una relación "tiene muchos" (uno a muchos).
     *
     * @param string $relatedModel La clase del modelo relacionado.
     * @param string $foreignKey La clave foránea en la tabla del modelo relacionado.
     * @param string $localKey La clave primaria en la tabla actual.
     * @return HasMany
     */
    protected function hasMany(string $relatedModel, string $foreignKey, string $localKey = 'id')
    {
        // Creamos una instancia de la clase de la relación
        $relation = new HasMany($relatedModel, $foreignKey, $localKey);
        $relation->setParent($this); // Le pasamos el modelo actual a la relación
        return $relation;
    }

    /**
     * Define una relación uno a uno.
     *
     * @param  string  $relatedModel La clase del modelo relacionado.
     * @param  string  $foreignKey La clave foránea en la tabla del modelo relacionado.
     * @param  string  $localKey La clave primaria en la tabla actual.
     * @return HasOne
     */
    protected function hasOne(string $relatedModel, string $foreignKey, string $localKey = 'id')
    {
        $relation = new HasOne($relatedModel, $foreignKey, $localKey);
        $relation->setParent($this);
        return $relation;
    }

    /**
     * Define una relación muchos a muchos.
     *
     * @param  string  $relatedModel El modelo final al que queremos llegar.
     * @param  string  $pivotTable La tabla intermedia que une los dos modelos.
     * @param  string  $foreignPivotKey La clave foránea en la tabla pivote que apunta al modelo actual.
     * @param  string  $relatedPivotKey La clave foránea en la tabla pivote que apunta al modelo relacionado.
     * @return \App\Database\Relations\BelongsToMany
     */
    protected function belongsToMany(
        string $relatedModel,
        string $pivotTable,
        string $foreignPivotKey,
        string $relatedPivotKey
    ) {
        $relation = new \App\Database\Relations\BelongsToMany(
            $relatedModel,
            $pivotTable,
            $foreignPivotKey,
            $relatedPivotKey
        );
        $relation->setParent($this);
        return $relation;
    }
}
