<?php

namespace App\Http\Interfaces;

use DateTime;

interface LogUsuariosInterface
{
    public function getId(): int;

    public function getUsuario(): ?UsuariosInterface;

    public function getAccion(): ?string;

    public function getDetalle(): ?string;

    public function getFecha(): DateTime;
}