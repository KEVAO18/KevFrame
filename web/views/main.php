<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_ENV['APP_NAME'] ?></title>
    <link rel="icon" type="image/x-icon" href="<?=$_ENV['APP_ICON']?>">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/color.css">
    <link rel="stylesheet" href="css/content.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/hw.css">
    <link rel="stylesheet" href="css/margin.css">
    <link rel="stylesheet" href="css/padding.css">
    <link rel="stylesheet" href="css/text.css">
    <link rel="stylesheet" href="css/principal.css">
</head>

<body>

    <header>
        <nav class="px-3-r">
            <ul>
                <li>
                    <a class="navbar-brand" href="/">
                        <?= $_ENV['APP_NAME'] ?>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    @yield('content')

    <footer class="container border-top mt-4" id="footer">
        <div class="row text-footer py-3 text-center">
            <div class="col-md-6 col-sm-12">
                <a class="text-decoration-none color-seco" href="">Sobre Nosotros</a>
            </div>
            <div class="col-md-6 col-sm-12">
                <a class="text-decoration-none color-seco" href="">Documentacion</a>
            </div>
        </div>
    
        <div class="text-center color-seco py-4">
            <span class="">&copy; {{ date('Y') }} <?= $_ENV['APP_NAME'] ?></span>
        </div>
    </footer>
</body>

</html>