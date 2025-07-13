<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Productos;
use PDO;

class ProductosHandler{
    private $db;

    public function __construct(){
        $this->db = Database::getInstance();
    }
    
    public function create(Productos $producto): int{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `productos` 
            (`nombre`, `descripcion`, `unidades`, `precio`, `estado_id`) 
            VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getUnidades(),
            $producto->getPrecio(),
            $producto->getEstadoId()->getId()
        ]);

        return $db->lastInsertId();
    }

    public function update(Productos $producto): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `productos` SET
            `nombre` = ?, `descripcion` = ?, `unidades` = ?, 
            `precio` = ?, `estado_id` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $producto->getNombre(),
            $producto->getDescripcion(),
            $producto->getUnidades(),
            $producto->getPrecio(),
            $producto->getEstadoId()->getId(),
            $producto->getId()
        ]);
    }

    public function delete(int $id): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `productos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?Productos{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `productos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        return $datos ? new Productos(
            $datos['id'],
            $datos['nombre'],
            $datos['descripcion'],
            $datos['unidades'],
            $datos['precio'],
            (new EstadosProductoHandler())->getById($datos['estado_id'])
        ) : null;
    }

    public function getAll(): array{
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM productos')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new Productos(
                $datos['id'],
                $datos['nombre'],
                $datos['descripcion'],
                $datos['unidades'],
                $datos['precio'],
                (new EstadosProductoHandler())->getById($datos['estado_id'])
            ),
            $result
        ) ?? [];
    }
}