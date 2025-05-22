<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\ProductoAtributoValorInterface;
use App\Core\Database;
use PDO;
use App\Models\ProductoAtributoValor;

class ProductoAtributoValorHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(ProductoAtributoValorInterface $pav): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `producto_atributo_valor` 
            (`producto_id`, `valor_atributo_id`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $pav->getProductoId(),
            $pav->getValorAtributoId()
        ]);

        return $db->lastInsertId();
    }

    public function update(): bool {
        throw new \Exception("Este campo no se puede actualizar"); 
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `producto_atributo_valor` WHERE `producto_id` =? AND `valor_atributo_id` =? ');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id, int $campo): ?ProductoAtributoValor {
        $db = $this->db->getConnection();
        $stmt = ($campo == 1) ?
            $db->prepare('SELECT * FROM `producto_atributo_valor` WHERE `producto_id` = ?') :
            $db->prepare('SELECT * FROM `producto_atributo_valor` WHERE `valor_atributo_id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new ProductoAtributoValor(
            $datos['producto_id'],
            $datos['valor_atributo_id']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `producto_atributo_valor`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new ProductoAtributoValor(
                $row['producto_id'],
                $row['valor_atributo_id']
            ),
            $result
        ) ?? [];
    }
}