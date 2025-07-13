<?php

namespace App\Http\Interfaces;

interface ProductosInterface
{
    public function getId(): int;

    public function getNombre(): string;

    public function getDescripcion(): ?string;

    public function getUnidades(): int;

    public function getPrecio(): float;

    public function getEstadoId(): EstadosProductoInterface;
}