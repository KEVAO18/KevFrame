<?php

namespace App\Http\Handlers;

use App\Core\Database;
use App\Models\Productos;
use PDO;

class ProcesosAlmacenadosHandler
{

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
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     *
     * @return array
     * @access public
     * 
     */
    public function getTop(int $number): array
    {
        $db = $this->db->getConnection();
        $stmt = $db->prepare("CALL `productos_mas_vendidos`(?)");
        $stmt->execute([$number]);
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
}
