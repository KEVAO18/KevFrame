<?php

namespace App\Http\Handlers;

use App\Http\Interfaces\CarritoInterface;
use App\Core\Database;
use PDO;

class CarritoHandler implements CarritoInterface {
    
    /**
     * @var Database
     * @access private
     *
     */
    private $db;

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
    private int $usuario;

    /**
     * @var string
     * @access private
     *
     */
    private string $fechaCreacion;

    /**
     * @access public
     * @param int $id
     * @param int $usuario
     * @param string $fechaCreacion
     * @return void
     * 
     */
    public function __construct(int $id, int $usuario, string $fechaCreacion){
        $this->db = new Database();
        $this->id = $id;
        $this->usuario = $usuario;
        $this->fechaCreacion = $fechaCreacion;
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
    public function getUsuario(): int{
        return $this->usuario;
    }

    /** 
     * @return string
     * @access public
     *
    */
    public function getFechaCreacion(): string{
        return $this->fechaCreacion;
    }

    /**
     * @return int
     * @access public
     *
     */
    public function create(CarritoInterface $carrito): int
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO `carritos` 
            (`usuario`, `fecha_creacion`) 
            VALUES (?, ?)'
        );

        $stmt->execute([
            $carrito->getUsuario(),
            $carrito->getFechaCreacion()
        ]);

        return $db->lastInsertId();
    }

    /**
     * @param CarritoInterface $carrito
     * @return bool
     * @access public
     *
     */
    public function update(CarritoInterface $carrito): bool
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare(
            'UPDATE `carritos` SET
            `usuario` = ?, `fecha_creacion` = ?
            WHERE `id` = ?'
        );

        return $stmt->execute([
            $carrito->getUsuario(),
            $carrito->getFechaCreacion(),
            $carrito->getId()
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
        $stmt = $db->prepare('DELETE FROM `carritos` WHERE `id` = ?');
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
        $stmt = $db->prepare('SELECT * FROM `carritos` WHERE `id` = ?');
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
        return $db->query('SELECT * FROM `carritos`')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}