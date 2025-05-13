<?php

namespace App\Http\Interfaces;

use DateTime;

interface FacturaInterface
{
    public function getId(): string;

    public function getUsuario(): int;

    public function getFecha(): DateTime; // Assuming datetime is represented as string

    public function getTotal(): float;
}