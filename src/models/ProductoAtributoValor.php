<?php

namespace App\Models;

use App\Http\Interfaces\ProductoAtributoValorInterface;

class ProductoAtributoValor implements ProductoAtributoValorInterface{
    private int $productoId;
    private int $valorAtributoId;

    public function __construct(int $productoId, int $valorAtributoId) {
        $this->productoId = $productoId;
        $this->valorAtributoId = $valorAtributoId;
    }

    public function getProductoId(): int{
        return $this->productoId;
    }

    public function getValorAtributoId(): int{
        return $this->valorAtributoId;
    }
}