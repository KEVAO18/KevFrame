<?php

namespace App\Http\Interfaces;

interface FacturaInterface
{
    public function getId(): string;

    public function getUsuario(): int;

    public function getFecha(): string; // Assuming datetime is represented as string

    public function getTotal(): float;
}