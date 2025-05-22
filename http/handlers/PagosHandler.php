<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\PagosInterface;
use App\Core\Database;
use PDO;
use App\Models\Pagos;
use DateTime;

class PagosHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(PagosInterface $pago): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pagos` 
            (`pedido`, `metodo_pago`, `monto`, `fecha`, `estado`) 
            VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $pago->getPedido(),
            $pago->getMetodoPago(),
            $pago->getMonto(),
            $pago->getFecha(),
            $pago->getEstado()
        ]);

        return $db->lastInsertId();
    }

    public function update(PagosInterface $pago): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pagos`
            SET `pedido` =?, `metodo_pago` =?, `monto` =?, `fecha` =?, `estado` =?
            WHERE `id` =?'  
        );

        return $stmt->execute([
            $pago->getPedido(),
            $pago->getMetodoPago(),
            $pago->getMonto(),
            $pago->getFecha(),
            $pago->getEstado(),
            $pago->getId()
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
            $datos['pedido'],
            $datos['metodo_pago'],
            $datos['monto'],
            new DateTime($datos['fecha']),
            $datos['estado']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `pagos`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Pagos(
                $row['id'],
                $row['pedido'],
                $row['metodo_pago'],
                $row['monto'],
                new DateTime($row['fecha']),
                $row['estado']
            ),
            $result
        ) ?? [];
    }
}