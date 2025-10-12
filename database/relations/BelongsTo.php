<?php

namespace App\Database\Relations;

use App\Models\Model;

class BelongsTo implements Relation
{
    protected Model $relatedModel;
    protected Model $parent;
    protected string $foreignKey;
    protected string $ownerKey;

    public function __construct(string $relatedModel, string $foreignKey, string $ownerKey)
    {
        $this->relatedModel = new $relatedModel();
        $this->foreignKey = $foreignKey;
        $this->ownerKey = $ownerKey;
    }

    public function setParent(Model $parent): void
    {
        $this->parent = $parent;
    }

    public function getResults()
    {
        // El valor de la clave foránea en el modelo padre
        $foreignKeyValue = $this->parent->{$this->foreignKey};

        // Buscamos el modelo relacionado usando la clave del propietario
        return $this->relatedModel
            ->where($this->ownerKey, '=', $foreignKeyValue)
            ->get(true)[0] ?? null; // Devolvemos el primer resultado como objeto
    }
}