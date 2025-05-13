<?php

namespace App\Models;

use App\Http\Interfaces\EstadosProductoInterface;

class EstadosProducto implements EstadosProductoInterface{
    private int $id;
    private string $nombre;

    public function __construct(int $id, string $nombre) {
        $this->id = $id;
        $this->nombre = $nombre;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }
}