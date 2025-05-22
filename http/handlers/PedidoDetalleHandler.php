<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\PedidoDetalleInterface;
use App\Core\Database;
use PDO;
use App\Models\PedidoDetalle;

class PedidoDetalleHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(PedidoDetalleInterface $pedido_detalle): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pedido_detalle` 
            (`pedido`, `producto`, `cantidad`) 
            VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $pedido_detalle->getPedido(),
            $pedido_detalle->getProducto(),
            $pedido_detalle->getCantidad()
        ]);

        return $db->lastInsertId();
    }

    public function update(PedidoDetalleInterface $pedido_detalle): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pedido_detalle` SET
            `pedido` =?,
            `producto` =?,
            `cantidad` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $pedido_detalle->getPedido(),
            $pedido_detalle->getProducto(),
            $pedido_detalle->getCantidad()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `pedido_detalle` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }
    
    public function getById(int $id): ?PedidoDetalle {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `pedido_detalle` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new PedidoDetalle(
            $datos['id'],
            $datos['pedido'],
            $datos['producto'],
            $datos['cantidad']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `pedido_detalle`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new PedidoDetalle(
                $row['id'],
                $row['pedido'],
                $row['producto'],
                $row['cantidad']
            ),
            $result
        ) ?? [];
    }
}