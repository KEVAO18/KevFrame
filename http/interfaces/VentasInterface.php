<?php

namespace App\Http\Interfaces;

interface VentasInterface
{
    public function getId(): int;

    public function getProducto(): int;

    public function getCantidad(): int;

    public function getFactura(): string;

    public function getFecha(): string; // Assuming DATETIME is represented as string
}