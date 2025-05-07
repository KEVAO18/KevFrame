<?php

namespace App\Http\Interfaces;

interface CarritoInterface
{
    public function getId(): int;

    public function getUsuario(): int;

    public function getFechaCreacion(): string; // Assuming DATETIME is represented as string
}