<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\VentasInterface;
use App\Core\Database;
use PDO;
use App\Models\Ventas;
use DateTime;

class VentasHandler  {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(VentasInterface $ventas): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `ventas` 
            (`producto`, `cantidad`, `factura`, `fecha`) 
            VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $ventas->getProducto(),
            $ventas->getCantidad(),
            $ventas->getFactura(),
            $ventas->getFecha()
        ]);

        return $db->lastInsertId();
    }

    public function update(VentasInterface $ventas): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `ventas`
            SET `producto` =?, `cantidad` =?, `factura` =?, `fecha` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $ventas->getProducto(),
            $ventas->getCantidad(),
            $ventas->getFactura(),
            $ventas->getFecha(),
            $ventas->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `ventas` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?Ventas {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `ventas` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Ventas(
            $datos['id'],
            $datos['producto'],
            $datos['cantidad'],
            $datos['factura'],
            new DateTime($datos['fecha'])
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `ventas`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Ventas(
                $row['id'],
                $row['producto'],
                $row['cantidad'],
                $row['factura'],
                new DateTime($row['fecha'])
            ),
            $result
        ) ?? [];
    }
}