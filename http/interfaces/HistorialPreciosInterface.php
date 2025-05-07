<?php

namespace App\Http\Interfaces;

interface HistorialPreciosInterface
{
    public function getId(): int;

    public function getProductoId(): int;

    public function getPrecio(): float;

    public function getFechaInicio(): string; // O DateTime

    public function getFechaFin(): ?string; // O DateTime, puede ser NULL
}