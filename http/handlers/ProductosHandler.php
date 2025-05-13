<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\ProductosInterface;
use App\Core\Database;
use App\Models\Producto;
use PDO;

class ProductosHandler{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }
    
    public function create(ProductosInterface $producto): int{
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
            $producto->getEstadoId()
        ]);

        return $db->lastInsertId();
    }

    public function update(ProductosInterface $producto): bool{
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
            $producto->getEstadoId(),
            $producto->getId()
        ]);
    }

    public function delete(int $id): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `productos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?Producto{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `productos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        return $datos ? new Producto(
            $datos['id'],
            $datos['nombre'],
            $datos['descripcion'],
            $datos['unidades'],
            $datos['precio'],
            $datos['estado_id']
        ) : null;
    }

    public function getAll(): array{
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM productos')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Producto(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['unidades'],
                $row['precio'],
                $row['estado_id']
            ),
            $result
        ) ?? [];
    }
}