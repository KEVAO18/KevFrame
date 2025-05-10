<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="text-danger mb-4">Error 404 - Página no encontrada</h1>
    <p>La página que buscas no existe.</p>
    <a href="?p=menu" class="btn btn-primary">Volver al Menú</a>
</body>
</html>