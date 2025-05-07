<?php

namespace App\Http\Interfaces;

interface CuponesInterface
{
    public function getId(): int;

    public function getCodigo(): string;

    public function getDescuento(): float;

    public function getTipo(): string;

    public function getValidoDesde(): string; // Assuming DATE is represented as string

    public function getValidoHasta(): string; // Assuming DATE is represented as string

    public function getLimiteUso(): int;
}