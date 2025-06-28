<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Http\Handlers\Categoriashandler;
use App\Http\Handlers\ProcesosAlmacenadosHandler;
use App\Http\Handlers\ProductoCategoriaHandler;
use App\Http\Handlers\ProductosHandler;
use App\Http\Handlers\VistasHandler;

class ProductoController
{
    public function index(){
        $productos = new ProductosHandler();
        $productos = $productos->getAll();

        $vistas = new VistasHandler;
        $categorias = $vistas->getProductosxCategoria();

        View::render("componentes/main/productos", compact('productos', 'categorias'));
    }

    public function show(Request $request){

        $producto = new ProcesosAlmacenadosHandler();
        $productos_mas_vendidos = $producto->getTop(4);
        $atributos = $producto->getAtributos($request->get('id'));
        $producto = $producto->getProductoParaMostrar($request->get('id'));

        View::render("componentes/main/producto", compact('producto', 'atributos', 'productos_mas_vendidos'));
    }

    public function filtro(Request $request){
        
        $procesos = new ProcesosAlmacenadosHandler;
        $vistas = new VistasHandler;
        $productos = $procesos->getFiltroCategoria($request->get('valor'));
        $categorias = $vistas->getProductosxCategoria();

        View::render("componentes/main/productos", compact('productos', 'categorias'));
    }

    public function nuevos(){

        $procesos = new ProcesosAlmacenadosHandler;
        $vistas = new VistasHandler;
        $productos = $procesos->nuevos(16);
        $categorias = $vistas->getProductosxCategoria();

        View::render("componentes/main/productos", compact('productos', 'categorias'));
    }
    
    public function topVentas(){
        
        $procesos = new ProcesosAlmacenadosHandler;
        $vistas = new VistasHandler;
        $productos = $procesos->getTop(16);
        $categorias = $vistas->getProductosxCategoria();

        View::render("componentes/main/productos", compact('productos', 'categorias'));
    }
}