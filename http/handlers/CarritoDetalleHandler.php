<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CarritoDetalleInterface;
use App\Core\Database;
use App\Models\CarritoDetalle;
use PDO;

class CarritoDetalleHandler {

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
    public function __construct(){
        $this->db = Database::getInstance();
    }

    /**
     * @param CarritoDetalleInterface $carritoDetalle
     * @return int
     * @access public
     *  
     */
    public function create(CarritoDetalleInterface $carritoDetalle): int{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `carrito_detalle` 
            (`carrito`, `producto`, `cantidad`) 
            VALUES (?, ?, ?)'
        );

        $stmt->execute([
            $carritoDetalle->getCarrito(),
            $carritoDetalle->getProducto(),
            $carritoDetalle->getCantidad()
        ]);

        return $db->lastInsertId();
    }

    /**
     * @param CarritoDetalleInterface $carritoDetalle
     * @return bool
     * @access public
     * 
     */
    public function update(CarritoDetalleInterface $carritoDetalle): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `carrito_detalle` SET
            `carrito` = ?, `producto` = ?, `cantidad` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $carritoDetalle->getCarrito(),
            $carritoDetalle->getProducto(),
            $carritoDetalle->getCantidad(),
            $carritoDetalle->getId()
        ]);
    }

    /**
     * @param int $id
     * @return bool
     * @access public
     * 
     */
    public function delete(int $id): bool{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('DELETE FROM `carrito_detalle` WHERE `id` = ?');
        return $stmt->execute([$id]);
    }

    /**
     * @param int $id
     * @return array|null
     * @access public
     * 
     */
    public function getById(int $id): ?CarritoDetalle{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `carrito_detalle` WHERE `id` = ?');
        $stmt->execute([$id]);
        $datos = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

        return $datos? new CarritoDetalle(
            $datos['id'],
            $datos['carrito'],
            $datos['producto'],
            $datos['cantidad'] 
        ) : null;
    }

    /**
     * @return array
     * @access public
     *
     */
    public function getAll(): array{
        $db = $this->db->getConnection();
        $result = $db->query('SELECT * FROM `carrito_detalle`')
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($datos) => new CarritoDetalle(
                $datos['id'],
                $datos['carrito'],
                $datos['producto'],
                $datos['cantidad']
            ),
            $result
        );
    }
}