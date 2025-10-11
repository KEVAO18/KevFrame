<?php

namespace App\Database\Factories;

abstract class Factory
{
    /**
     * El nombre del modelo que corresponde al factory.
     */
    protected string $model;

    /**
     * El número de modelos a crear.
     */
    protected int $count = 1;

    /**
     * La definición de los atributos del modelo.
     */
    abstract public function definition(): array;

    /**
     * Especifica cuántos modelos deben ser creados.
     */
    public function count(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Crea y guarda los modelos en la base de datos.
     */
    public function create(): void
    {

        for ($i = 0; $i < $this->count; $i++) {
            $attributes = $this->definition();
            $modelInstance = new $this->model($attributes);
            $modelInstance->save();
        }
    }
}