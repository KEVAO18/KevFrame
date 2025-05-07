<?php

namespace App\Http\Interfaces;

interface MetodosPagoInterface
{
    public function getId(): int;

    public function getNombre(): string;

    public function getDescripcion(): ?string;
}