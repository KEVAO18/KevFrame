<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CuponesInterface;
use App\Core\Database;
use PDO;

class CuponesHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(CuponesInterface $cupon): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `cupones` 
            (`codigo`, `descuento`, `tipo`, `valido_desde`, `valido_hasta`, `limite_uso`) 
            VALUES (?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $cupon->getCodigo(),
            $cupon->getDescuento(),
            $cupon->getTipo(),
            $cupon->getValidoDesde(),
            $cupon->getValidoHasta(),
            $cupon->getLimiteUso()
        ]);

        return $db->lastInsertId();
    }

    public function update(CuponesInterface $cupon): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `cupones` SET
            `codigo` = ?, `descuento` = ?, `tipo` = ?, `valido_desde` = ?, `valido_hasta` = ?, `limite_uso` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $cupon->getCodigo(),
            $cupon->getDescuento(),
            $cupon->getTipo(),
            $cupon->getValidoDesde(),
            $cupon->getValidoHasta(),
            $cupon->getLimiteUso(),
            $cupon->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `cupones` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `cupones` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `cupones`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}