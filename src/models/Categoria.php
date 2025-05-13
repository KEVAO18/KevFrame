<?php

namespace App\Models;

use App\Http\Interfaces\CategoriasInterface;

class Categoria implements CategoriasInterface {
    private int $id;
    private string $descripcion;

    public function __construct(int $id, string $descripcion) {
        $this->id = $id;
        $this->descripcion = $descripcion; 
    }

    public function getId(): int {
        return $this->id;
    }

    public function getDescripcion(): string {
        return $this->descripcion;
    }

}