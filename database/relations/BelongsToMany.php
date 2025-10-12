<?php

namespace App\Database\Relations;

use App\Core\Database;
use App\Models\Model;
use PDO;

class BelongsToMany implements Relation
{
    protected Model $relatedModel;
    protected Model $parent;
    protected string $pivotTable;
    protected string $foreignPivotKey; // Clave foránea del modelo padre en la tabla pivote
    protected string $relatedPivotKey; // Clave foránea del modelo relacionado en la tabla pivote

    public function __construct(
        string $relatedModel,
        string $pivotTable,
        string $foreignPivotKey,
        string $relatedPivotKey
    ) {
        $this->relatedModel = new $relatedModel();
        $this->pivotTable = $pivotTable;
        $this->foreignPivotKey = $foreignPivotKey;
        $this->relatedPivotKey = $relatedPivotKey;
    }

    public function setParent(Model $parent): void
    {
        $this->parent = $parent;
    }

    public function getResults()
    {
        $db = Database::getInstance();
        
        $parentKey = $this->parent->{$this->parent->primaryKey};
        $relatedTable = $this->relatedModel->table;
        $relatedPrimaryKey = $this->relatedModel->primaryKey;

        $sql = "SELECT {$relatedTable}.* FROM {$relatedTable}
                INNER JOIN {$this->pivotTable}
                ON {$relatedTable}.{$relatedPrimaryKey} = {$this->pivotTable}.{$this->relatedPivotKey}
                WHERE {$this->pivotTable}.{$this->foreignPivotKey} = ?";

        $stmt = $db->query($sql, [$parentKey]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Convertir los resultados en instancias del modelo relacionado
        $collection = [];
        foreach ($results as $row) {
            $collection[] = new $this->relatedModel($row);
        }

        return $collection;
    }
}