<?php

namespace App\Http\Interfaces;

interface EstadosPagoInterface
{
    public function getId(): int;

    public function getNombre(): string;
}