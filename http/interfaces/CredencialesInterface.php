<?php

namespace App\Http\Interfaces;

interface CredencialesInterface
{
    public function getId(): int;

    public function getUsuario(): int;

    public function getTipo(): int;
}