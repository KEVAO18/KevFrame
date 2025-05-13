<?php

namespace App\Models;

use App\Http\Interfaces\ProductoCategoriaInterface;

class ProductoCategoria implements ProductoCategoriaInterface{
    private int $productoId;
    private int $categoriaId;

    public function __construct(int $productoId, int $categoriaId) {
        $this->productoId = $productoId;
        $this->categoriaId = $categoriaId;
    }

    public function getProductoId(): int{
        return $this->productoId;
    }

    public function getCategoriaId(): int{
        return $this->categoriaId;
    }
}