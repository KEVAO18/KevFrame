<?php

namespace App\Database\Relations;

use App\Models\Model;

class HasMany implements Relation
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

        // Buscamos todos los modelos relacionados que coincidan con la clave foránea
        return $this->relatedModel
            ->where($this->foreignKey, '=', $localKeyValue)
            ->get(true); // Devolvemos una colección de modelos
    }
}