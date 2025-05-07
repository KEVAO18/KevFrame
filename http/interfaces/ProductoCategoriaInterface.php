<?php

namespace App\Http\Interfaces;

interface ProductoCategoriaInterface
{
    public function getProductoId(): int;

    public function getCategoriaId(): int;
}