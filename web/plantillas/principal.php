<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['APP_NAME'] ?></title>
    <link href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="public/css/principal.css">
    <link rel="icon" type="image/x-icon" href="<?= $_ENV['APP_ICON'] ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-text-ligth border-bottom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $_ENV['APP_BASE_URL'] ?>">
                <?= $_ENV['APP_NAME'] ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navItems" aria-controls="navItems" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navItems">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="<?= $_ENV['APP_BASE_URL'] ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Productos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Todos</a></li>
                            <li><a class="dropdown-item" href="#">Nuevos</a></li>
                            <li><a class="dropdown-item" href="#">Mas Vendidos</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    @yield('content')

    <footer class="site-footer bg-text-white py-5" id="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6">
                    <h3 class="h5">Eclipse Cósmico</h3>
                    <p class="">Linea de aromas creada con aceites esenciales, en los que unimos la ciencia de la Aromaterapia y la Cromaterapia para crear mágicos elixires.</p>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h4 class="h6 text-uppercase">Enlaces Útiles</h4>
                    <ul class="list-unstyled">
                        <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Productos</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h4 class="h6 text-uppercase">Síguenos</h4>
                    <ul class="list-unstyled">
                        <li class="nav-item">1</li>
                        <li class="nav-item">2</li>
                        <li class="nav-item">3</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center text-muted small">
                <p class="mb-0">&copy; 2024 Kevao. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>