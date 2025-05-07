<?php

namespace App\Http\Interfaces;

interface CuponesUsoInterface
{
    public function getId(): int;

    public function getCupon(): int;

    public function getUsuario(): int;

    public function getFechaUso(): string; // Assuming DATETIME is represented as string
}