<?php

namespace App\Database\Relations;

use App\Models\Model;

class HasOne implements Relation
{
    protected Model $relatedModel;
    protected Model $parent;
    protected string $foreignKey;
    protected string $localKey;

    public function __construct(string $relatedModel, string $foreignKey, string $localKey)
    {
        $this->relatedModel = new $relatedModel();
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
    }

    public function setParent(Model $parent): void
    {
        $this->parent = $parent;
    }

    public function getResults()
    {
        // El valor de la clave local en el modelo padre
        $localKeyValue = $this->parent->{$this->localKey};

        // Buscamos el modelo relacionado que coincida con la clave foránea
        return $this->relatedModel
            ->where($this->foreignKey, '=', $localKeyValue)
            ->get(true)[0] ?? null; // Devolvemos el primer resultado como objeto o null
    }
}