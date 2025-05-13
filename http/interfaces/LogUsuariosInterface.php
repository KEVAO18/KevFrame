<?php

namespace App\Http\Interfaces;

use DateTime;

interface LogUsuariosInterface
{
    public function getId(): int;

    public function getUsuario(): ?int;

    public function getAccion(): ?string;

    public function getDetalle(): ?string;

    public function getFecha(): DateTime;
}