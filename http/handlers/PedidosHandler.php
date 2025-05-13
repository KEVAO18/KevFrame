<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\PedidosInterface;
use App\Core\Database;
use App\Models\Pedidos;
use PDO;

class PedidosHandler
{
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    public function create(PedidosInterface $pedido): int{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `pedidos` 
            (`usuario`, `fecha`, `estado`, `total`) 
            VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $pedido->getUsuario(),
            $pedido->getFecha(),
            $pedido->getEstado(),
            $pedido->getTotal()
        ]);

        return $db->lastInsertId();
    }

    public function update(PedidosInterface $pedido): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `pedidos` SET
            `usuario` = ?, `fecha` = ?, `estado` = ?, `total` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $pedido->getUsuario(),
            $pedido->getFecha(),
            $pedido->getEstado(),
            $pedido->getTotal(),
            $pedido->getId()
        ]);
    }

    public function delete(int $id): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `pedidos` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?Pedidos{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `pedidos` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC);

        return $datos ? new Pedidos(
            $datos['id'],
            $datos['usuario'],
            $datos['fecha'],
            $datos['estado'],
            $datos['total'] 
        ) : null;
    }

    public function getAll(): array{
        $db = $this->db->getConnection();
        $result =  $db->query('SELECT * FROM `pedidos`')
        ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($datos){
            return new Pedidos(
                $datos['id'],
                $datos['usuario'],
                $datos['fecha'],
                $datos['estado'],
                $datos['total']
            );
        }, $result) ?? [];
    }
}