<?php

namespace App\Http\Interfaces;

interface LogUsuariosInterface
{
    public function getId(): int;

    public function getUsuario(): ?int; // Puede ser NULL

    public function getAccion(): ?string;

    public function getDetalle(): ?string;

    public function getFecha(): string; // O DateTime
}