<?php
// pagina para comprobar que php se conecta bien a la base de datos
require_once __DIR__ . '/includes/conexion.php';

$tablas = $conexion->query('SHOW TABLES')->fetch_all(MYSQLI_NUM);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prueba de conexión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
  <div class="alert alert-success">
    <strong>Conexión exitosa</strong> a la base de datos <?= DB_NAME ?>
    (servidor <?= $conexion->host_info ?>, versión <?= $conexion->server_info ?>).
  </div>
  <h2 class="h5">Tablas encontradas</h2>
  <ul class="list-group">
    <?php foreach ($tablas as $fila): ?>
      <li class="list-group-item"><?= $fila[0] ?></li>
    <?php endforeach; ?>
  </ul>
</body>
</html>
