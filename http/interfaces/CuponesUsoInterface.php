<?php

namespace App\Http\Interfaces;

use DateTime;

interface CuponesUsoInterface
{
    public function getId(): int;

    public function getCupon(): CuponesInterface;

    public function getUsuario(): UsuariosInterface;

    public function getFechaUso(): DateTime; // Assuming DATETIME is represented as string

}