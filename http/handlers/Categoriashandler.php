<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CategoriasInterface;
use App\Core\Database;
    use PDO;

    class Categoriashandler{

    /**
     * @var Database
     * @access private
     *
     */
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create(CategoriasInterface $categorias): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `categorias` 
            (`descripcion`) 
            VALUES (?)'
        );

        $stmt->execute([
            $categorias->getDescripcion()
        ]);

        return $db->lastInsertId();
    }

    public function update(CategoriasInterface $categorias): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `categorias` SET
            `descripcion` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $categorias->getDescripcion(),
            $categorias->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `categorias` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `categorias` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getAll(): array
    {
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `categorias`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}