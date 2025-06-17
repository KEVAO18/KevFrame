<?php

use App\Core\View;

View::import('plantillas/principal');


View::section('content', function ($datos) {

?>

    <main class="container-fluid pt-3">
        <div class="row">
            <aside class="col-md-3 col-sm-12">
                <article class="">
                    <h4 class="fw-bold">Categorias</h4>
                    <ul class="list-group">
                        <?php foreach ($datos['categorias'] as $categorias) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <a class="fw-light text-decoration-none text-dark" href="<?= $_ENV['APP_BASE_URL'] ?>Productos/filtro/categoria/<?= $categorias['id'] ?>">
                                        <?= $categorias['categoria'] ?>
                                    </a>
                                </div>
                                <span class="badge text-bg-primary rounded-pill">
                                    <?= $categorias['cantidad'] ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <a class="fw-light text-decoration-none text-dark" href="<?= $_ENV['APP_BASE_URL'] ?>Productos">
                                    Todos
                                </a>
                            </div>
                        </li>
                    </ul>
                </article>
            </aside>
            <section class="col-md-9 col-sm-12 border-start">
                <article class="row">
                    <?php foreach ($datos['productos'] as $producto) : ?>
                        <div class="col-md-3 col-sm-6 mt-4">
                            <a href="<?= $_ENV['APP_BASE_URL'] ?>Producto/<?= $producto->getId() ?>" class="text-decoration-none">
                                <div class="card w-100 h-100 shadow card-producto">
                                    <img src="<?= $_ENV['IMG_FOLDER'] . "productos/" . $producto->getId() ?>.png" class="card-img-top" alt="...">
                                    <div class="card-body bg-seco text-light">
                                        <h5 class="card-title"><?= $producto->getNombre() ?></h5>
                                        <p class="card-text"><?= $producto->getDescripcion() ?></p>
                                        <span>$ <?= $producto->getPrecio() ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </article>
            </section>
        </div>
    </main>

<?php

});

?>