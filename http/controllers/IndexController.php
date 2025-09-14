<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\SessionManager;
use App\Core\View;

class IndexController {

    public function index() {


        $sm = SessionManager::getInstance();
        $sm->start();

        $data = [
            'nombre' => 'Kevin',
            'tareas' => ['Estudiar', 'Programar', 'Comer']
        ];
        
        View::render('main/home', $data);

    }

}

?>