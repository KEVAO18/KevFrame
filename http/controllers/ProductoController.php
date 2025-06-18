<?php

namespace App\Http\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Http\Handlers\Categoriashandler;
use App\Http\Handlers\ProcesosAlmacenadosHandler;
use App\Http\Handlers\ProductoCategoriaHandler;
use App\Http\Handlers\ProductosHandler;

class ProductoController
{
    public function index(){
        $productos = new ProductosHandler();
        $productos = $productos->getAll();

        $categorias = new ProcesosAlmacenadosHandler;
        $categorias = $categorias->getProductosxCategoria();

        View::render("componentes/logout/productos", compact('productos', 'categorias'));
    }

    public function show(Request $request){

        $producto = new ProcesosAlmacenadosHandler();
        $productos_mas_vendidos = $producto->getTop(4);
        $atributos = $producto->getAtributos($request->get('id'));
        $producto = $producto->getProductoParaMostrar($request->get('id'));

        View::render("componentes/logout/producto", compact('producto', 'atributos', 'productos_mas_vendidos'));
    }

    public function filtro(Request $request){
        
        $categorias = new ProcesosAlmacenadosHandler;
        $productos = $categorias->getFiltroCategoria($request->get('valor'));
        $categorias = $categorias->getProductosxCategoria();

        View::render("componentes/logout/productos", compact('productos', 'categorias'));
    }

    public function nuevos(){

        $categorias = new ProcesosAlmacenadosHandler;
        $productos = $categorias->nuevos(16);
        $categorias = $categorias->getProductosxCategoria();

        View::render("componentes/logout/productos", compact('productos', 'categorias'));
    }
    
    public function topVentas(){
        
        $categorias = new ProcesosAlmacenadosHandler;
        $productos = $categorias->getTop(16);
        $categorias = $categorias->getProductosxCategoria();

        View::render("componentes/logout/productos", compact('productos', 'categorias'));
    }
}