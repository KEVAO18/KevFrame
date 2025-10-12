<?php
require __DIR__ . '/vendor/autoload.php';

use App\Templates\KevTemplateEngine;
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

include __DIR__.'/src/Core/helpers.php';

KevTemplateEngine::registerCommonDirectives();



include __DIR__.'/src/Core/routes.php';


?>