<?php

namespace App\Http\Interfaces;

use DateTime;

interface PagosInterface
{
    public function getId(): int;

    public function getPedido(): PedidosInterface;

    public function getMetodoPago(): MetodosPagoInterface;

    public function getMonto(): float;

    public function getFecha(): DateTime; // O DateTime, dependiendo de cómo se maneje

    public function getEstado(): EstadosPagoInterface;
}