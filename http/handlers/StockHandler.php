<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\StockInterface;
use App\Core\Database;
use PDO;
use App\Models\Stock;

class StockHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(StockInterface $stock): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `stock` 
            (`producto`, `agotado`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $stock->getProducto(),
            $stock->getAgotado()
        ]);

        return $db->lastInsertId();
    }

    public function update(StockInterface $stock): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `stock`
            SET `producto` =?, `agotado` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $stock->getProducto(),
            $stock->getAgotado(),
            $stock->getId()  
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `stock` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?Stock {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `stock` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Stock(
            $datos['id'],
            $datos['producto'],
            $datos['agotado']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `stock`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Stock(
                $row['id'],
                $row['producto'],
                $row['agotado']
            ),
            $result
        ) ?? [];
    }
}