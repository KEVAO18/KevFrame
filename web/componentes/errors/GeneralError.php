<?php

use App\Core\SessionManager;
use App\Core\View;

View::section('content', function ($datos) {

    ?>
    
    <main>
        <div class="container py-5">
            <h1 class="text-center display-2"><?=$datos['ErrorCode']?></h1>
            <h2 class="text-center display-3"><?=$datos['msg']?></h2>
        </div>
    </main>

    <?php

});

?>