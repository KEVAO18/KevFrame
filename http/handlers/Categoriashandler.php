<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Categoria;
use PDO;

    class Categoriashandler {

    /**
     * @var Database
     * @access private
     * 
     */
    private Database $db;

    /**
     * @return void
     * @access public
     * 
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(Categoria $categorias): int {
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

    public function update(Categoria $categorias): bool {
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

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `categorias` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?Categoria {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `categorias` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new Categoria(
            $datos['id'],
            $datos['descripcion'] 
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `categorias`')
            ->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(
            fn($datos) => new Categoria(
                $datos['id'],
                $datos['descripcion']
            ),
            $result
        ) ?? [];
    }
}