<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\HistorialPreciosInterface;
use App\Core\Database;
use PDO;
use App\Models\HistorialPrecios;

class HistorialPreciosHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(HistorialPreciosInterface $historial): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `historial_precios` 
            (`producto_id`, `precio`, `fecha_inicio`, `fecha_fin`) 
            VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $historial->getProductoId(),
            $historial->getPrecio(),
            $historial->getFechaInicio(),
            $historial->getFechaFin()
        ]);

        return $db->lastInsertId();
    }

    public function update(HistorialPreciosInterface $historial): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `historial_precios`
            SET `producto_id` = ?, `precio` = ?, `fecha_inicio` = ?, `fecha_fin` = ?
            WHERE `id` = ?' 
        );

        return $stmt->execute([
            $historial->getProductoId(),
            $historial->getPrecio(),
            $historial->getFechaInicio(),
            $historial->getFechaFin(),
            $historial->getId()  
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `historial_precios` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?HistorialPrecios {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `historial_precios` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new HistorialPrecios(
            $datos['id'],
            $datos['producto_id'],
            $datos['precio'],
            $datos['fecha_inicio'],
            $datos['fecha_fin']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `historial_precios`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new HistorialPrecios(
                $row['id'],
                $row['producto_id'],
                $row['precio'],
                $row['fecha_inicio'],
                $row['fecha_fin']
            ),
            $result
        ) ?? [];
    }
}