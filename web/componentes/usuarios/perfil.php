<?php

use App\Core\SessionManager;
use App\Core\View;

$sm = new SessionManager();
$sm->start();

if ($sm->get('user_id') == null) {
    header('Location: '. $_ENV['APP_BASE_URL']); 
}else{
    View::import('plantillas/logged');
}


View::section('content', function ($datos) {

?>

<main class="container py-2">
    <div id="" class="my-4">
        <h2 class="fs-2 fw-light"><?=$datos['usuario']->getFullname()?></h2>
        <p class="color-seco"><?=$datos['usuario']->getEmail()?></p>
    </div>

    <details name="requirements" class="card p-4 mb-4">
        <summary class="fs-4 fw-light">
            Informacion Personal
            <a href="" class="color-seco text-decoration-none fs-5">Editar</a>
        </summary>
        <hr>
        <div class="row" id="dniField">
            <div class="col-6">
                <p class="fs-5 fw-light">DNI: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['usuario']->getDni()?></p>
            </div>
        </div>
        <div class="row" id="fullnameField">
            <div class="col-6">
                <p class="fs-5 fw-light">Nombre Completo: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['usuario']->getFullname()?></p>
            </div>
        </div>
        <div class="row" id="fullnameField">
            <div class="col-6">
                <p class="fs-5 fw-light">E-mail: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['usuario']->getEmail()?></p>
            </div>
        </div>
    </details>
    <details name="requirements" class="card p-4 mb-4">
        <summary class="fs-4 fw-light">
            Informacion de Envio
            <a href="" class="color-seco text-decoration-none fs-5">Editar</a>
        </summary>
        <hr>
        <div class="row" id="paisField">
            <div class="col-6">
                <p class="fs-5 fw-light">País: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['direcciones']->getPais()?></p>
            </div>
        </div>
        <div class="row" id="departamentoField">
            <div class="col-6">
                <p class="fs-5 fw-light">Departamento: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['direcciones']->getDepartamento()?></p>
            </div>
        </div>
        <div class="row" id="ciudadField">
            <div class="col-6">
                <p class="fs-5 fw-light">Ciudad: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['direcciones']->getCiudad()?></p>
            </div>
        </div>
        <div class="row" id="direccionField">
            <div class="col-6">
                <p class="fs-5 fw-light">Direccíon: </p>
            </div>
            <div class="col-6">
                <p class="fs-5 fw-light"><?=$datos['direcciones']->getDireccion()?></p>
            </div>
        </div>
    </details>
</main>

<?php

});
?>