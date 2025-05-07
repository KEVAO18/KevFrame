<?php

namespace App\Http\Interfaces;

interface ProductoAtributoValorInterface
{
    public function getProductoId(): int;

    public function getValorAtributoId(): int;
}