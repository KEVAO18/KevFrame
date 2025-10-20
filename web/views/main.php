<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['APP_NAME'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?=$_ENV['APP_ICON']?>">
    @vite('resources/css/app.css')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <?= $_ENV['APP_NAME'] ?>
            </a>
        </div>
    </nav>

    @yield('content')

    <footer class="container border-top mt-4" id="footer">
        <div class="row text-footer py-3 text-center">
            <div class="col-md-6 col-sm-12">
                <a class="text-decoration-none" target="_blank" href="https://www.kevao.tech/">Sobre Mi</a>
            </div>
            <div class="col-md-6 col-sm-12">
                <a class="text-decoration-none" target="_blank" href="https://github.com/KEVAO18/KevFrame/wiki">Documentacion</a>
            </div>
        </div>
    
        <div class="text-center color-seco py-4">
            <span class="">&copy; {{ date('Y') }} <?= $_ENV['APP_NAME'] ?></span>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>

</html>