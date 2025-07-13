<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\ProductoCategoriaInterface;
use App\Core\Database;
use PDO;
use App\Models\ProductoCategoria;

class ProductoCategoriaHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
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

    public function update(): bool {
        throw new \Exception("Este campo no se puede actualizar");
    }

    public function delete(int $producto, int $categoria): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `producto_categoria` WHERE `producto_id` =? and `categoria_id` =?');
        return $stmt->execute([
            $producto,
            $categoria
        ]); 
    }

    public function getByProduct(int $id, int $campo): ?ProductoCategoria {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `producto_categoria` WHERE `producto_id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC)?: null;
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new ProductoCategoria(
            (new ProductosHandler())->getById($datos['producto_id']),
            (new Categoriashandler())->getById($datos['categoria_id'])
        ) : null;
    }

    public function getByCategoria(int $id, int $campo): array {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `producto_categoria` WHERE `categoria_id` =?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC)?: null;
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return array_map(
            fn($row) => new ProductoCategoria(
                (new ProductosHandler())->getById($row['producto_id']),
                (new Categoriashandler())->getById($row['categoria_id'])
            ),
            $datos
        ) ?? [];
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `producto_categoria`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new ProductoCategoria(
                (new ProductosHandler())->getById($datos['producto_id']),
                (new Categoriashandler())->getById($datos['categoria_id'])
            ),
            $result
        ) ?? [];
    }
}