<?php

use App\Core\SessionManager;
use App\Core\View;

$sm = new SessionManager();
$sm->start();

View::import('plantillas/logged');


View::section('content', function ($datos) {

?>



<?php

});
?>