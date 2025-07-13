<?php

namespace App\Http\Handlers;

use App\Core\Database;
use PDO;
use App\Models\PedidoDetalle;

class PedidoDetalleHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(PedidoDetalle $pedido_detalle): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pedido_detalle` 
            (`pedido`, `producto`, `cantidad`) 
            VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $pedido_detalle->getPedido()->getId(),
            $pedido_detalle->getProducto()->getId(),
            $pedido_detalle->getCantidad()
        ]);

        return $db->lastInsertId();
    }

    public function update(PedidoDetalle $pedido_detalle): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pedido_detalle` SET
            `pedido` =?,
            `producto` =?,
            `cantidad` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $pedido_detalle->getPedido()->getId(),
            $pedido_detalle->getProducto()->getId(),
            $pedido_detalle->getCantidad(),
            $pedido_detalle->getId()
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
            (new PedidosHandler())->getById($datos['pedido']),
            (new ProductosHandler())->getById($datos['producto']),
            $datos['cantidad']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `pedido_detalle`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new PedidoDetalle(
                $datos['id'],
                (new PedidosHandler())->getById($datos['pedido']),
                (new ProductosHandler())->getById($datos['producto']),
                $datos['cantidad']
            ),
            $result
        ) ?? [];
    }
}