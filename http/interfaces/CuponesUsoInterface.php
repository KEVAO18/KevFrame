<?php

namespace App\Http\Interfaces;

use DateTime;

interface CuponesUsoInterface
{
    public function getId(): int;

    public function getCupon(): int;

    public function getUsuario(): int;

    public function getFechaUso(): DateTime; // Assuming DATETIME is represented as string

}