<?php

namespace App\Http\Interfaces;

use DateTime;

interface VentasInterface
{
    public function getId(): int;

    public function getProducto(): ProductosInterface;

    public function getCantidad(): int;

    public function getFactura(): FacturaInterface;

    public function getFecha(): DateTime; // Assuming DATETIME is represented as string
}