<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\View;

class IndexController {

    public function index() {

        View::render("componentes/main/home");

    }

}

?>