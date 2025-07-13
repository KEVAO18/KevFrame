<?php

namespace App\Http\Interfaces;

interface ProductoCategoriaInterface
{
    public function getProductoId(): ProductosInterface;

    public function getCategoriaId(): CategoriasInterface;
}