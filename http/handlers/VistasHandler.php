<?php

namespace App\Http\Handlers;

use App\Core\Database;
use PDO;

class VistasHandler {

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

    public function getProductosxCategoria(){

        $db = $this->db->getConnection();
        $stmt = $db->prepare("SELECT * FROM `productos_por_categoria`");

        $stmt->execute();
        $datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $datos ?? [];

    }

}
