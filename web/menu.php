<?php
require_once __DIR__ . '/../src/Core/Database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Bienvenido a nuestra tienda</h1>
    <div class="list-group">
        <a href="?p=productos" class="list-group-item list-group-item-action">Ver Productos</a>
        <a href="?p=carrito" class="list-group-item list-group-item-action">Carrito de Compras</a>
        <a href="?p=contacto" class="list-group-item list-group-item-action">Contacto</a>
    </div>
</body>
</html>