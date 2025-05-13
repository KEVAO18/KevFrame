<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\EstadosPagoInterface;
use App\Core\Database;
use PDO;

class EstadosPagoHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(EstadosPagoInterface $estadosPago): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `estados_pago` 
            (`nombre`) 
            VALUES (?)'
        );

        $stmt->execute([
            $estadosPago->getNombre()
        ]);

        return $db->lastInsertId();
    }

    public function update(EstadosPagoInterface $estadosPago): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `estados_pago` SET 
            `nombre` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $estadosPago->getNombre(),
            $estadosPago->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `estados_pago` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `estados_pago` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `estados_pago`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}