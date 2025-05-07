<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link href="../vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require __DIR__ . '/../web/header.php'; ?>
    <main class="container py-4">
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <?php require __DIR__ . '/../web/product_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </main>
    <script src="../vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>
</body>

</html>