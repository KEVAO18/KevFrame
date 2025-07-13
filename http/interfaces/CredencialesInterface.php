<?php

namespace App\Http\Interfaces;

interface CredencialesInterface
{
    public function getId(): int;

    public function getUsuario(): UsuariosInterface;

    public function getTipo(): TipoCredencialInterface;
}