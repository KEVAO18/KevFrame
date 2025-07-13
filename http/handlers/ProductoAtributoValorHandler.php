<?php

namespace App\Http\Handlers;

use App\Core\Database;
use PDO;
use App\Models\ProductoAtributoValor;

class ProductoAtributoValorHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(ProductoAtributoValor $pav): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `producto_atributo_valor` 
            (`producto_id`, `valor_atributo_id`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $pav->getProductoId()->getId(),
            $pav->getValorAtributoId()->getId()
        ]);

        return $db->lastInsertId();
    }

    public function update(): bool {
        throw new \Exception("Este campo no se puede actualizar");
    }

    public function delete(int $producto, int $valAtrib): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `producto_atributo_valor` WHERE `producto_id` =? AND `valor_atributo_id` =? ');
        return $stmt->execute([
            $producto,
            $valAtrib
        ]);
    }

    public function getByProduct(int $id): array {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `producto_atributo_valor` WHERE `producto_id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return array_map(
            fn($datos) => new ProductoAtributoValor(
                (new ProductosHandler())->getById($datos['producto_id']),
                (new ValoresAtributoHandler())->getById($datos['valor_atributo_id'])
            ),
            $datos
        ) ?? [];
    }

    public function getByAtributVal(int $id): array {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `producto_atributo_valor` WHERE `valor_atributo_id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return array_map(
            fn($datos) => new ProductoAtributoValor(
                (new ProductosHandler())->getById($datos['producto_id']),
                (new ValoresAtributoHandler())->getById($datos['valor_atributo_id'])
            ),
            $datos
        ) ?? [];
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `producto_atributo_valor`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new ProductoAtributoValor(
                (new ProductosHandler())->getById($datos['producto_id']),
                (new ValoresAtributoHandler())->getById($datos['valor_atributo_id'])
            ),
            $result
        ) ?? [];
    }
}