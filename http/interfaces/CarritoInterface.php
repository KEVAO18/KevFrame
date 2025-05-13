<?php

namespace App\Http\Interfaces;

use DateTime;

interface CarritoInterface
{
    public function getId(): int;

    public function getUsuario(): int;

    public function getFechaCreacion(): DateTime;
}