<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\DevolucionesInterface;
use App\Core\Database;
use PDO;

class DevolucionesHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(DevolucionesInterface $devolucion): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `devoluciones` 
            (`producto`, `factura`, `motivo`, `reembolso`, `estado`, `fecha_ingreso`, `fecha_final`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $devolucion->getProducto(),
            $devolucion->getFactura(),
            $devolucion->getMotivo(),
            $devolucion->getReembolso(),
            $devolucion->getEstado(),
            $devolucion->getFechaIngreso(),
            $devolucion->getFechaFinal()
        ]);

        return $db->lastInsertId();
    }

    public function update(DevolucionesInterface $devolucion): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `devoluciones` SET 
            `producto` = ?, `factura` = ?, `motivo` = ?, `reembolso` = ?, `estado` = ?, `fecha_ingreso` = ?, `fecha_final` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $devolucion->getProducto(),
            $devolucion->getFactura(),
            $devolucion->getMotivo(),
            $devolucion->getReembolso(),
            $devolucion->getEstado(),
            $devolucion->getFechaIngreso(),
            $devolucion->getFechaFinal(),
            $devolucion->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `devoluciones` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `devoluciones` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `devoluciones`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}