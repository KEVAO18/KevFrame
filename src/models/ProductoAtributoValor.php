<?php

namespace App\Models;

use App\Http\Interfaces\ProductoAtributoValorInterface;

class ProductoAtributoValor implements ProductoAtributoValorInterface{
    private Productos $productoId;
    private ValoresAtributo $valorAtributoId;

    public function __construct(Productos $productoId, ValoresAtributo $valorAtributoId) {
        $this->productoId = $productoId;
        $this->valorAtributoId = $valorAtributoId;
    }

    public function getProductoId(): Productos{
        return $this->productoId;
    }

    public function getValorAtributoId(): ValoresAtributo{
        return $this->valorAtributoId;
    }
}