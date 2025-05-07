<?php

namespace App\Http\Interfaces;

interface PedidosInterface
{
    public function getId(): int;

    public function getUsuario(): int;

    public function getFecha(): string; // Assuming DATETIME is represented as string

    public function getEstado(): string;

    public function getTotal(): float;
}