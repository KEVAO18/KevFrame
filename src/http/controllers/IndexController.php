<?php

namespace App\Http\Controllers;

use App\Core\Cli;
use App\Core\Request;
use App\Core\SessionManager;
use App\Core\View;

class IndexController {

    public function index() {


        $sm = SessionManager::getInstance();
        $sm->start();

        // Elimina los datos de ejemplo y pasa la versión del framework
        $data = [
            'version' => (new Cli())->getVersion() // Accede a la constante de la versión
        ];
        
        View::render('main/home', $data);

    }

}

?>