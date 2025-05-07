<?php

namespace App\Http\Interfaces;

interface DireccionesEnvioInterface
{
    public function getId(): int;

    public function getUsuario(): int;

    public function getDireccion(): string;

    public function getCiudad(): ?string;

    public function getDepartamento(): ?string;

    public function getPais(): ?string;

    public function getPrincipal(): ?bool;
}