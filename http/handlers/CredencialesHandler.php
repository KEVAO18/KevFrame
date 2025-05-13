<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CredencialesInterface;
use App\Core\Database;
    use PDO;

    class CredencialesHandler
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(CredencialesInterface $credenciales): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `credenciales` 
            (`usuario`, `tipo`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $credenciales->getUsuario(),
            $credenciales->getTipo()
        ]);

        return $db->lastInsertId();
    }

    public function update(CredencialesInterface $credenciales): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `credenciales` SET
            `usuario` = ?, `tipo` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $credenciales->getUsuario(),
            $credenciales->getTipo(),
            $credenciales->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `credenciales` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `credenciales` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `credenciales`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}