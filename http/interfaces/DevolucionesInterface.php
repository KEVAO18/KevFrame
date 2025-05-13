<?php

namespace App\Http\Interfaces;

use DateTime;

interface DevolucionesInterface{
    public function getId(): int;

    public function getProducto(): int;

    public function getFactura(): string;

    public function getMotivo(): string;

    public function getReembolso(): float;

    public function getEstado(): string;

    public function getFechaIngreso(): DateTime;

    public function getFechaFinal(): ?DateTime;
}