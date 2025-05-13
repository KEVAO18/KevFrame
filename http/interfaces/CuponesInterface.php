<?php

namespace App\Http\Interfaces;

use DateTime;

interface CuponesInterface
{
    public function getId(): int;

    public function getCodigo(): string;

    public function getDescuento(): float;

    public function getTipo(): string;

    public function getValidoDesde(): DateTime; // Assuming DATE is represented as string

    public function getValidoHasta(): DateTime; // Assuming DATE is represented as string

    public function getLimiteUso(): int;
}