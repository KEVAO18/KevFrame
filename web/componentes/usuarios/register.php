<?php

use App\Core\SessionManager;
use App\Core\View;

$sm = new SessionManager();
$sm->start();

if ($sm->get('user_id') == null) {
    View::import('plantillas/principal');
}else{
    header('Location: '. $_ENV['APP_BASE_URL']); 
}


View::section('content', function ($datos) {

?>

    <main>
        <div class="container">
            <div class="row my-4">
                <div class="col-md-10 mx-auto">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 banner-login-2 rounded-start">
                        </div>
                        <div class="col-md-8 col-sm-12 align-content-center border">
                            <div class="px-4 my-4">
                                <h3 class="color-seco text-center">Registro</h3>
                                <form action="<?= $_ENV['APP_BASE_URL'] ?>registro" method="POST">
                                    <div class="input-group mb-3">
                                        <label for="dni" class="input-group-text">Documento</label>
                                        <input type="number" class="form-control" id="dni" name="dni" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="fullname" class="input-group-text">Nombre</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="userName" class="input-group-text">usuario</label>
                                        <input type="text" class="form-control" id="userName" name="userName" required>
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="email" class="input-group-text">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                        <input type="email" class="form-control" id="repeat_email" name="repeat_email" required placeholder="repetir Email">
                                    </div>
                                    <div class="input-group mb-3">
                                        <label for="password" class="input-group-text">Contraseña</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <input type="password" class="form-control" id="repeat_pass" name="repeat_pass" required placeholder="repetir contraseña">
                                    </div>
                                    <div class="form-check form-switch my-2 align-items-lg-center">
                                        <input class="form-check-input" type="checkbox" value="acepto" id="tyc" switch name="tyc" required>
                                        <label class="form-check-label" for="tyc">
                                            Acepto los <a class="color-seco text-decoration-none" href="">terminos y condiciones</a>
                                        </label>
                                    </div>
                                    <div class="form-check form-switch my-2 align-items-lg-center">
                                        <input class="form-check-input" type="checkbox" value="acepto" id="privPoli" switch name="privPoli" required>
                                        <label class="form-check-label" for="privPoli">
                                            Acepto las <a class="color-seco text-decoration-none" href="">politica de privacidad</a>
                                        </label>
                                    </div>
                                    <div class="mb-3 d-grid">
                                        <button type="submit" class="btn btn-outline-dark">Completar Registro</button>
                                    </div>
                                </form>
                                <p class="text-center">
                                    <small class="">Ya estas registrado? puedes <a class="color-seco text-decoration-none" href="<?= $_ENV['APP_BASE_URL'] ?>iniciar">Iniciar sesion</a></small>
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