<?php

namespace App\Http\Interfaces;

interface CategoriasInterface
{
    public function getId(): int;

    public function getDescripcion(): string;
}