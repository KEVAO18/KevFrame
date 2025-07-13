<?php

namespace App\Http\Interfaces;

use DateTime;

interface CarritoInterface
{
    public function getId(): int;

    public function getUsuario(): UsuariosInterface;

    public function getFechaCreacion(): DateTime;
}