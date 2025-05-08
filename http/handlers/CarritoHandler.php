<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CarritoInterface;
use App\Core\Database;
use PDO;

class CarritoHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(CarritoInterface $carrito): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `carritos` 
            (`usuario`, `fecha_creacion`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $carrito->getUsuario(),
            $carrito->getFechaCreacion()
        ]);

        return $db->lastInsertId();
    }

    public function update(CarritoInterface $carrito): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `carritos` SET
            `usuario` = ?, `fecha_creacion` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $carrito->getUsuario(),
            $carrito->getFechaCreacion(),
            $carrito->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `carritos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `carritos` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `carritos`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}