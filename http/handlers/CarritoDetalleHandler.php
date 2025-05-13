<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CarritoDetalleInterface;
use App\Core\Database;
use PDO;

class CarritoDetalleHandler implements CarritoDetalleInterface {

    /**
     * @var Database
     * @access private
     *
     */
    private Database $db;

    /**
     * @var int
     * @access private
     *
     */
    private int $id;

    /**
     * @var int
     * @access private
     *
     */
    private int $carrito;

    /**
     * @var int
     * @access private
     *
     */
    private int $producto;

    /**
     * @var int
     * @access private
     *
     */
    private int $cantidad;

    /**
     * @param int $id
     * @param int $carrito
     * @param int $producto
     * @param int $cantidad
     * @return void
     * @access public
     *
     */
    public function __construct($id, $carrito, $producto, $cantidad){
        $this->db = new Database();
        $this->id = $id;
        $this->carrito = $carrito;
        $this->producto = $producto;
        $this->cantidad = $cantidad;
    }

    /**
     * @return int
     * @access public
     *
     */
    public function getId(): int{
        return $this->id;
    }

    /** 
     * @return int
     * @access public
     *
     */
    public function getCarrito(): int{
        return $this->carrito;
    }

    /**
     * @return int
     * @access public
     *
     */
    public function getProducto(): int{
        return $this->producto;
    }

    /**
     * @return int
     * @access public
     *
     */
    public function getCantidad(): int{
        return $this->cantidad;
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
    public function getById(int $id): ?array{
        $db = $this->db->getConnection();
        $stmt = $db->prepare('SELECT * FROM `carrito_detalle` WHERE `id` = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * @return array
     * @access public
     *
     */
    public function getAll(): array{
        $db = $this->db->getConnection();
        return $db->query('SELECT * FROM `carrito_detalle`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}