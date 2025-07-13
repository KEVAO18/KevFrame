<?php

namespace App\Http\Handlers;

use App\Core\Database;
use PDO;
use App\Models\Pagos;
use DateTime;

class PagosHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(Pagos $pago): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pagos` 
            (`pedido`, `metodo_pago`, `monto`, `fecha`, `estado`) 
            VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $pago->getPedido()->getId(),
            $pago->getMetodoPago()->getId(),
            $pago->getMonto(),
            $pago->getFecha(),
            $pago->getEstado()->getId()
        ]);

        return $db->lastInsertId();
    }

    public function update(Pagos $pago): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pagos`
            SET `pedido` =?, `metodo_pago` =?, `monto` =?, `fecha` =?, `estado` =?
            WHERE `id` =?'  
        );

        return $stmt->execute([
            $pago->getPedido()->getId(),
            $pago->getMetodoPago()->getId(),
            $pago->getMonto(),
            $pago->getFecha(),
            $pago->getEstado()->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `pagos` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?Pagos {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `pagos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Pagos(
            $datos['id'],
            (new PedidosHandler())->getById($datos['pedido']),
            (new MetodosPagoHandler())->getById($datos['metodo_pago']),
            $datos['monto'],
            new DateTime($datos['fecha']),
            (new EstadosPagoHandler())->getById($datos['estado'])
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `pagos`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new Pagos(
                $datos['id'],
                (new PedidosHandler())->getById($datos['pedido']),
                (new MetodosPagoHandler())->getById($datos['metodo_pago']),
                $datos['monto'],
                new DateTime($datos['fecha']),
                (new EstadosPagoHandler())->getById($datos['estado'])
            ),
            $result
        ) ?? [];
    }
}