<?php

namespace App\Http\Interfaces;

use DateTime;

interface VentasInterface
{
    public function getId(): int;

    public function getProducto(): int;

    public function getCantidad(): int;

    public function getFactura(): string;

    public function getFecha(): DateTime; // Assuming DATETIME is represented as string
}