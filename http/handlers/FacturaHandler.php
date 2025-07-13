<?php

namespace App\Http\Handlers;

use App\Models\Factura;
use App\Core\Database;
use DateTime;
use PDO;

class FacturaHandler {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create(Factura $factura): int {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `facturas` 
            (`usuario`, `fecha`, `total`) 
            VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $factura->getUsuario()->getDni(),
            $factura->getFecha(),
            $factura->getTotal()
        ]);

        return $db->lastInsertId();
    }

    public function update(Factura $factura): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `facturas`
            SET `usuario` = ?, `fecha` = ?, `total` = ?
            WHERE `id` = ?'
        ); 
        return $stmt->execute([
            $factura->getUsuario()->getDni(),
            $factura->getTotal(),
            $factura->getFecha(),
            $factura->getId() 
        ]);
    }

    public function delete(int $id): bool {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `facturas` WHERE `id` =?');
        return $stmt->execute([$id]); 
    }

    public function getById(int $id): ?Factura {
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `facturas` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos ? new Factura(
            $datos['id'],
            (new UsuariosHandler())->getById($datos['usuario']),
            new DateTime($datos['fecha']),
            $datos['total']
        ) : null;
    }

    public function getAll(): array {
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `facturas`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new Factura(
                $datos['id'],
                (new UsuariosHandler())->getById($datos['usuario']),
                new DateTime($datos['fecha']),
                $datos['total']
            ),
            $result
        ) ?? [];
    }
}