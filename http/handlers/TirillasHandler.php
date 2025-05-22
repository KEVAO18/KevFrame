<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\TirillasInterface;
use App\Core\Database;
use PDO;
use App\Models\Tirillas;

class TirillasHandler {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create(TirillasInterface $tirilla): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `tirillas` 
            (`pago_id`, `contenido`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $tirilla->getPagoId(),
            $tirilla->getContenido()
        ]);

        return $db->lastInsertId();
    }

    public function update(TirillasInterface $tirilla): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `tirillas`
            SET `pago_id` =?, `contenido` =?, `fecha_generacion` =?
            WHERE `id` =?'
        ); 

        return $stmt->execute([
            $tirilla->getPagoId(),
            $tirilla->getContenido(),
            $tirilla->getFechaGeneracion(),
            $tirilla->getId() 
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `tirillas` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?Tirillas {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `tirillas` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Tirillas(
            $datos['id'],
            $datos['pago_id'],
            $datos['contenido'],
            $datos['fecha_generacion']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `tirillas`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Tirillas(
                $row['id'],
                $row['pago_id'],
                $row['contenido'],
                $row['fecha_generacion']
            ),
            $result
        ) ?? [];
    }
}