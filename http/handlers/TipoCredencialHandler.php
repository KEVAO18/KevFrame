<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\TipoCredencialInterface;
use App\Core\Database;
use PDO;
use App\Models\TipoCredencial;

class TipoCredencialHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(TipoCredencialInterface $tipocredencial): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `tipocredencial` 
            (`descripcion`) 
            VALUES (?)'
        );

        $stmt->execute([
            $tipocredencial->getDescripcion()
        ]);

        return $db->lastInsertId();
    }

    public function update(TipoCredencialInterface $tipocredencial): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `tipocredencial`
            SET `descripcion` =?
            WHERE `id` =?'
        );

        return $stmt->execute([
            $tipocredencial->getDescripcion(),
            $tipocredencial->getId()  
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `tipocredencial` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?TipoCredencial {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `tipocredencial` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new TipoCredencial(
            $datos['id'],
            $datos['descripcion']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `tipocredencial`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new TipoCredencial(
                $row['id'],
                $row['descripcion']
            ),
            $result
        ) ?? [];
    }
}