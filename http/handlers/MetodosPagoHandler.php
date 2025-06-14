<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\MetodosPagoInterface;
use App\Core\Database;
use PDO;
use App\Models\MetodosPago;

class MetodosPagoHandler {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(MetodosPagoInterface $metodo): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `metodos_pago` 
            (`nombre`, `descripcion`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $metodo->getNombre(),
            $metodo->getDescripcion()
        ]);

        return $db->lastInsertId();
    }

    public function update(MetodosPagoInterface $metodo): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `metodos_pago` SET
            `nombre` = ?,
            `descripcion` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $metodo->getNombre(),
            $metodo->getDescripcion(),
            $metodo->getId()
        ]); 
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `metodos_pago` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?MetodosPago {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `metodos_pago` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new MetodosPago(
            $datos['id'],
            $datos['nombre'],
            $datos['descripcion'],
            new \DateTime($datos['fecha_creacion'])
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `metodos_pago`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new MetodosPago(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                new \DateTime($row['fecha_creacion'])
            ),
            $result
        ) ?? [];
    }
}