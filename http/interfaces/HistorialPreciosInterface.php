<?php

namespace App\Http\Interfaces;

use DateTime;

interface HistorialPreciosInterface
{
    public function getId(): int;

    public function getProductoId(): int;

    public function getPrecio(): float;

    public function getFechaInicio(): DateTime;

    public function getFechaFin(): ?DateTime;
}