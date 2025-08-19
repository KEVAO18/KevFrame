<?php

use App\Core\SessionManager;
use App\Core\View;

$sm = new SessionManager();
$sm->start();

if ($sm->get('user_id') == null) {
    View::import('plantillas/main');
}else{
    View::import('plantillas/logged');   
}



View::section('content', function ($datos) {

    ?>
    
    <main>
        <div class="container py-5">
            <h1 class="text-center display-2"><?=$datos['ErrorCode']?></h1>
            <h2 class="text-center display-3"><?=$datos['msg']?></h2>
            <div class="d-grid pt-4">
                <a href="/" class="btn btn-outline-dark">Volver al inicio</a>
            </div>
        </div>
    </main>

    <?php

});

?>