<?php

use App\Core\SessionManager;
use App\Core\View;

$sm = new SessionManager();
$sm->start();

if ($sm->get('user_id') == null) {
    View::import('plantillas/principal');
}else{
    View::import('plantillas/logged');   
}


View::section('content', function ($datos) {

?>

    <main class="container py-4">
        <h2 class="text-dark text-decoration-none fw-light">
            <a href="<?= $_ENV['APP_BASE_URL'] ?>Productos/filtro/categoria/<?= $datos['producto'][0]['categoria_id'] ?>" class="text-dark text-decoration-none fw-light color-seco"><?= $datos['producto'][0]['categoria'] ?> /</a>
            <span href="<?= $_ENV['APP_BASE_URL'] ?>" class="text-dark text-decoration-none fw-light"><?= $datos['producto'][0]['nombre'] ?></span>
        </h2>

        <div class="row mt-5">
            <div class="col-md-6 col-sm-12 text-center mb-4">
                <img src="<?= $_ENV['IMG_FOLDER'] ?>productos/<?= $datos['producto'][0]['id'] ?>.png" class="img-fluid rounded img-prod" alt="<?= $datos['producto'][0]['nombre'] ?>">
            </div>
            <div class="card col-md-6 col-sm-12 d-flex align-items-center text-center py-4">
                <div class="">
                    <h3 class="fw-normal">Acerca de</h3>
                    <p class="text-dark fs-4 fw-light"><?= $datos['producto'][0]['descripcion'] ?></p>
                    <?php foreach ($datos['atributos'] as $atrib) : ?>
                        <h5 class="text-dark fs-3 fw-normal"><?= $atrib['nombre'] ?></h5>
                        <p class="text-dark fs-4 fw-light"><?= $atrib['valor'] ?></p>
                    <?php endforeach; ?>
                    <p class="text-dark fs-3 fw-normal">$ <?= $datos['producto'][0]['precio'] ?></p>
                    <form action="" method="post">
                        <div class="input-group mb-3">
                            <input type="number" max="<?= $datos['producto'][0]['unidades'] ?>" min="0" class="form-control" placeholder="Cantidad" aria-label="Cantidad" aria-describedby="btnAddCar">
                            <button class="btn btn-outline-secondary" type="button" id="btnAddCar">Añadir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section>
            <h2 class="text-center display-5 mt-5">Mas Vendidos</h2>
            <hr>
            <article class="row">
                <?php foreach ($datos['productos_mas_vendidos'] as $producto) : ?>
                    <div class="col-md-3 col-sm-6 mt-4">
                        <a href="<?= $_ENV['APP_BASE_URL'] ?>Producto/<?= $producto->getId() ?>" class="text-decoration-none">
                            <div class="card w-100 h-100 shadow card-producto">
                                <img
                                    src="<?= $_ENV['IMG_FOLDER'] . "productos/" . $producto->getId() ?>.png"
                                    class="card-img-top img-card"
                                    title="<?= $producto->getNombre() ?>"
                                    alt="<?= $producto->getNombre() ?>"
                                >
                                <div class="card-body bg-seco text-light">
                                    <h5 class="card-title"><?= $producto->getNombre() ?></h5>
                                    <p class="card-text"><?= $producto->getDescripcion() ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </article>
        </section>
    </main>

<?php

});

?>