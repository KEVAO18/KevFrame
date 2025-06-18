<?php

use App\Core\View;

View::import('plantillas/principal');


View::section('content', function ($datos) {

?>

    <main>
        <div class="container">
            <div class="row my-4">
                <div class="col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 banner-login rounded-start">
                        </div>
                        <div class="col-md-8 col-sm-12 align-content-center h-60-v border">
                            <div class="text-center px-4">
                                <h3 class="color-seco">Iniciar Sesión</h3>
                                <form action="<?= $_ENV['APP_BASE_URL'] ?>login" method="POST">
                                    <div class="input-group my-4">
                                        <label for="email" class="input-group-text">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="input-group my-4">
                                        <label for="password" class="input-group-text">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="mb-3 d-grid">
                                        <button type="submit" class="btn btn-outline-dark">Iniciar Sesión</button>
                                    </div>
                                </form>
                                <p class="text-center">
                                    <small class="">Aun no estas registrado? puedes <a class="color-seco text-decoration-none" href="<?= $_ENV['APP_BASE_URL'] ?>registro">Registrarte</a></small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php

});
?>