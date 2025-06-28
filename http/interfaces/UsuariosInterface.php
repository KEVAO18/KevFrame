<?php

namespace App\Http\Interfaces;

interface UsuariosInterface
{
    public function getDni(): int;

    public function getFullname(): string;

    public function getEmail(): string;

    public function getPass(): string;

    public function getSalt(): string;

    public function getIsUsuarioActivo(): bool;

}