<?php

namespace App\Http\Interfaces;

interface StockInterface
{
    public function getId(): int;

    public function getProducto(): ?int; // Puede ser NULL

    public function getAgotado(): ?int; // tinyint, puede ser NULL
}