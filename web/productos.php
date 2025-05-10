<?php
require_once __DIR__ . '/../src/Core/Database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Nuestros Productos</h1>
    <div class="list-group">
        <div class="list-group-item">Producto 1</div>
        <div class="list-group-item">Producto 2</div>
        <div class="list-group-item">Producto 3</div>
    </div>
    <a href="?p=menu" class="btn btn-secondary mt-3">Volver al Menú</a>
</body>
</html>