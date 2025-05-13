<?php

namespace App\Models;

use App\Http\Interfaces\MetodosPagoInterface;

class MetodosPago implements MetodosPagoInterface{
    private int $id;
    private string $nombre;
    private string $descripcion;

    public function __construct(int $id, string $nombre, ?string $descripcion) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
    }

    public function getId(): int {
        return $this->id; 
    }

    public function getNombre(): string {
        return $this->nombre; 
    }

    public function getDescripcion():?string {
        return $this->descripcion;
    }
}