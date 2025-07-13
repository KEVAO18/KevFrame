<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Productos;
use App\Models\Usuario;
use PDO;

class ProcesosAlmacenadosHandler {

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
     *
     * @return array
     * @access public
     * 
     */
    public function getTop(int $number): array{
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `productos_mas_vendidos`(?)");
        $stmt->execute([$number]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return array_map(
            fn($row) => new Productos(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['unidades'],
                $row['precio'],
                (new EstadosProductoHandler())->getById($row['estado_id'])
            ),
            $datos
        ) ?? [];
        
    }

    /**
     *
     * @return array
     * @access public
     * 
     */
    public function nuevos(int $number): array{
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `nuevos`(?)");
        $stmt->execute([$number]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return array_map(
            fn($row) => new Productos(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['unidades'],
                $row['precio'],
                (new EstadosProductoHandler())->getById($row['estado_id'])
            ),
            $datos
        ) ?? [];
        
    }

    public function paginacionProductos(int $start, int $end): array{
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `paginacion_productos`(?, ?)");
        $stmt->execute([
            $start,
            $end
        ]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => new Productos(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['unidades'],
                $row['precio'],
                $row['estado_id']
            ),
            $datos
        ) ?? [];
        
    }

    public function getProductoParaMostrar(int $id): array{

        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `producto_para_mostrar`(?)");

        $stmt->execute([$id]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $datos ?? [];
    }

    public function getAtributos(string $producto): array{
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `atributos_producto`(?);");

        $stmt->execute([$producto]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $datos ?? [];
    }

    public function getFiltroCategoria(int $id): array{

        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `filtro_categoria`(?)");

        $stmt->execute([$id]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        return array_map(
            fn($row) => new Productos(
                $row['id'],
                $row['nombre'],
                $row['descripcion'],
                $row['unidades'],
                $row['precio'],
                (new EstadosProductoHandler())->getById($row['estado_id'])
            ), 
            $datos
        );
    }

    public function login(string $email): ?Usuario{
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `login`(?)");

        $stmt->execute([$email]);
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? [];

        return $datos ? new Usuario(
            $datos['dni'],
            $datos['fullname'],
            $datos['email'],
            $datos['pass'],
            $datos['salt'],
            $datos['usuario_activo']
        ) : null;
    }
}
