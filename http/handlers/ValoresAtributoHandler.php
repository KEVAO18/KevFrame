<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\ValoresAtributoInterface;
use App\Core\Database;
use PDO;
use App\Models\ValoresAtributo;

class ValoresAtributoHandler  {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(ValoresAtributoInterface $valores_atributo): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `valores_atributo` 
            (`atributo_id`, `valor`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $valores_atributo->getAtributoId(),
            $valores_atributo->getValor()
        ]);

        return $db->lastInsertId();
    }

    public function update(ValoresAtributoInterface $valores_atributo): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `valores_atributo`
            SET `atributo_id` =?, `valor` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $valores_atributo->getAtributoId(),
            $valores_atributo->getAtributoId(),
            $valores_atributo->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `valores_atributo` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?ValoresAtributo {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `valores_atributo` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new ValoresAtributo(
            $datos['id'],
            $datos['atributo_id'],
            $datos['valor']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `valores_atributo`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new ValoresAtributo(
                $row['id'],
                $row['atributo_id'],
                $row['valor']
            ),
            $result
        ) ?? [];
    }
}