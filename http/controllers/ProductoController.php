<?php

namespace App\Http\Controllers;

class ProductoController
{
    public function index(){
        echo "Productos";
    }

    public function show($id){
        echo "Producto $id";
    }

    public function filtro($filtro, $valor){
        echo "Producto $filtro $valor";
    }
}