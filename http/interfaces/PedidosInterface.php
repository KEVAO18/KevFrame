<?php

namespace App\Http\Interfaces;

use DateTime;

interface PedidosInterface
{
    public function getId(): int;

    public function getUsuario(): UsuariosInterface;

    public function getFecha(): DateTime;

    public function getEstado(): string;

    public function getTotal(): float;
}