<?php

namespace App\Http\Interfaces;

interface ProductoAtributoValorInterface
{
    public function getProductoId(): ProductosInterface;

    public function getValorAtributoId(): ValoresAtributoInterface;
}