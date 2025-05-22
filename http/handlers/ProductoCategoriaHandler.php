<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\ProductoCategoriaInterface;
use App\Core\Database;
use PDO;
use App\Models\ProductoCategoria;

class ProductoCategoriaHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(ProductoCategoriaInterface $producto_categoria): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `producto_categoria` 
            (`producto_id`, `categoria_id`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $producto_categoria->getProductoId(),
            $producto_categoria->getCategoriaId()
        ]);

        return $db->lastInsertId();
    }

    public function update(ProductoCategoriaInterface $pav): bool {
        throw new \Exception("Este campo no se puede actualizar"); 
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `producto_categoria` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id, int $campo): ?ProductoCategoria {
        $db = $this->db->getConnection();
        $stmt = ($campo == 1)? $db->prepare('SELECT * FROM `producto_categoria` WHERE `producto_id` = ?') : $db->prepare('SELECT * FROM `producto_categoria` WHERE `categoria_id` =?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC)?: null;
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new ProductoCategoria(
            $datos['producto_id'],
            $datos['categoria_id']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `producto_categoria`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new ProductoCategoria(
                $row['producto_id'],
                $row['categoria_id']
            ),
            $result
        ) ?? [];
    }
}