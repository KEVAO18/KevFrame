<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\EstadosPago;
use PDO;

class EstadosPagoHandler {
    
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

    public function create(EstadosPago $estadosPago): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `estados_pago` 
            (`nombre`) 
            VALUES (?)'
        );

        $stmt->execute([
            $estadosPago->getNombre()
        ]);

        return $db->lastInsertId();
    }

    public function update(EstadosPago $estadosPago): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `estados_pago` SET 
            `nombre` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $estadosPago->getNombre(),
            $estadosPago->getId()
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `estados_pago` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?EstadosPago {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `estados_pago` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new EstadosPago(
            $datos['id'],
            $datos['nombre']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result =  $db->query('SELECT * FROM `estados_pago`')
            ->fetchAll(PDO::FETCH_ASSOC);
        
        return array_map(
            fn ($datos) => new EstadosPago(
                $datos['id'],
                $datos['nombre']
            ),
            $result
        ) ?? [];
    }
}