<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\PedidosInterface;
use App\Core\Database;
use PDO;

class PedidosHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(PedidosInterface $pedido): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pedidos` 
            (`usuario`, `fecha`, `estado`, `total`) 
            VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $pedido->getUsuario(),
            $pedido->getFecha(),
            $pedido->getEstado(),
            $pedido->getTotal()
        ]);

        return $db->lastInsertId();
    }

    public function update(PedidosInterface $pedido): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pedidos` SET
            `usuario` = ?, `fecha` = ?, `estado` = ?, `total` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $pedido->getUsuario(),
            $pedido->getFecha(),
            $pedido->getEstado(),
            $pedido->getTotal(),
            $pedido->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `pedidos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `pedidos` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `pedidos`')
        ->fetchAll(PDO::FETCH_ASSOC);
    }
}