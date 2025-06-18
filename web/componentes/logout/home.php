<?php

use App\Core\View;

View::import('plantillas/principal');


View::section('content', function ($datos) {
?>

    <header class="container">
        <div id="mainCarousel" class="carousel slide">
            <div class="carousel-inner" id="car_items">

                <?php

                $banners = file_get_contents($_ENV['DOCS_FOLDER'] . 'banners.json');
                $banners = json_decode($banners, true);

                $is_active = true;
                foreach ($banners['homepage'] as $key) {

                ?>

                    <div class="carousel-item my-4 <?= ($is_active) ? "active" : "" ?>">
                        <img src="<?= $_ENV['IMG_FOLDER'] . "banners/" . $key['name'] ?>" class="d-block w-100 rounded-4" alt="<?= $key['name'] ?>">
                    </div>

                <?php

                    $is_active = false;
                }

                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>

    <main class="container py-2">
        <h2 class="text-center display-5">Mas Vendidos</h2>
        <hr>
        <div class="row">
            <?php foreach ($datos as $producto) : ?>
                <div class="col-md-3 col-sm-6 mt-4">
                    <a href="<?= $_ENV['APP_BASE_URL'] ?>Producto/<?= $producto->getId() ?>" class="text-decoration-none">
                        <div class="card w-100 h-100 shadow card-producto">
                            <img 
                                src="<?= $_ENV['IMG_FOLDER'] . "productos/" . $producto->getId() ?>.png" 
                                class="card-img-top img-card" 
                                title="<?=$producto->getNombre()?>" 
                                alt="<?=$producto->getNombre()?>"
                            >
                            <div class="card-body bg-seco text-light">
                                <h5 class="card-title"><?=$producto->getNombre()?></h5>
                                <p class="card-text"><?=$producto->getDescripcion()?></p>
                            </div>
                        </div>
                    </a>
                </div>

            <?php endforeach; ?>
        </div>


    </main>


<?php

});

?>