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

    <main class="container">
        <h2 class="text-center">Mas Vendidos</h2>
        <div class="row">
            <?php foreach ($datos as $producto) : ?>
                <div class="col-sm-3">
                    <div class="card w-100 h-100 shadow">
                        <img src="<?= $_ENV['IMG_FOLDER'] . "productos/" . $producto->getId() ?>.png" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?=$producto->getNombre()?></h5>
                            <p class="card-text"><?=$producto->getDescripcion()?></p>
                        </div>
                        <div class="d-grid garp-2">
                            <a href="#" style="border-radius: 0;" class="btn btn-outline-dark">Ver</a>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </main>


<?php

});

?>