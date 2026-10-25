<?php
// pagina protegida: si no hay sesion manda al prototipo
require_once __DIR__ . '/../includes/sesion.php';
require_once __DIR__ . '/../includes/funciones.php';

exigir_login('prototipo_sesion.php');

// datos que se guardaron en la sesion
$nombre = $_SESSION['usuario_nombre'];
$inicio = date('d-m-Y H:i:s', $_SESSION['inicio']);
$minutos = round((time() - $_SESSION['inicio']) / 60, 1);
$libros_en_carrito = array_sum($_SESSION['carrito'] ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zona privada | Web SPA - Proyecto Final</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/estilos.css" rel="stylesheet">
</head>
<body>
  <header class="encabezado encabezado-chico">
    <div class="container"><h1>🔒 Zona privada</h1></div>
  </header>

  <main class="container py-4">
    <div class="caja-formulario caja-ancha">
      <h2 class="h5 mb-3">Datos de tu sesión</h2>
      <table class="table table-bordered">
        <tr><th>Usuario</th><td><?= h($nombre) ?></td></tr>
        <tr><th>ID de sesión</th><td><code><?= h(session_id()) ?></code></td></tr>
        <tr><th>Inicio de sesión</th><td><?= h($inicio) ?></td></tr>
        <tr><th>Tiempo activo</th><td><?= $minutos ?> minutos</td></tr>
        <tr><th>Libros en el carrito</th><td><?= (int) $libros_en_carrito ?></td></tr>
      </table>
      <a href="prototipo_sesion.php" class="btn btn-outline-dark">Volver al prototipo</a>
    </div>
  </main>
</body>
</html>
