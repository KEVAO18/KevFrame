<?php

namespace App\Http\Interfaces;

interface TipoCredencialInterface
{
    public function getId(): int;

    public function getDescripcion(): string;
}