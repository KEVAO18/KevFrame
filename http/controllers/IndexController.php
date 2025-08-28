<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\SessionManager;
use App\Core\View;

class IndexController {

    public function index() {


        $sm = new SessionManager();
        $sm->start();

        if ($sm->get('user_id') == null) {
            View::layout('main');
        }else{
            View::layout('logged');   
        }

        View::render("componentes/main/home");

    }

}

?>