<?php

namespace App\Http\Interfaces;

interface DevolucionesInterface
{
    public function getId(): int;

    public function getProducto(): int;

    public function getFactura(): string;

    public function getMotivo(): string;

    public function getReembolso(): float;

    public function getEstado(): string;

    public function getFechaIngreso(): string; // Assuming DATETIME is represented as string

    public function getFechaFinal(): ?string; // Assuming DATETIME is represented as string, nullable
}