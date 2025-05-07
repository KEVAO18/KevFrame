<?php

namespace App\Http\Interfaces;

interface PagosInterface
{
    public function getId(): int;

    public function getPedido(): int;

    public function getMetodoPago(): int;

    public function getMonto(): float;

    public function getFecha(): string; // O DateTime, dependiendo de cómo se maneje

    public function getEstado(): int;
}