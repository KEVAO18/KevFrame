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
    <?php

});

?>