<?php

namespace App\Http\Controllers;

use App\Core\View;
use App\Http\Handlers\ProcesosAlmacenadosHandler;
use App\Models\Productos;

class IndexController{

    public function index(){

        $productos_mas_vendidos = new ProcesosAlmacenadosHandler();

        $productos_mas_vendidos = $productos_mas_vendidos->getTop(4);

        View::render("componentes/main/home", $productos_mas_vendidos);
        
    }

}
