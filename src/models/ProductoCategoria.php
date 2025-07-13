<?php

namespace App\Models;

use App\Http\Interfaces\ProductoCategoriaInterface;

class ProductoCategoria implements ProductoCategoriaInterface{
    private Productos $productoId;
    private Categoria $categoriaId;

    public function __construct(Productos $productoId, Categoria $categoriaId) {
        $this->productoId = $productoId;
        $this->categoriaId = $categoriaId;
    }

    public function getProductoId(): Productos{
        return $this->productoId;
    }

    public function getCategoriaId(): Categoria{
        return $this->categoriaId;
    }
}