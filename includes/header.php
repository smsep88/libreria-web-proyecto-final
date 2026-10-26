<?php
// parte de arriba de las paginas php (head, encabezado y menu)
// la variable $titulo se define antes de incluir este archivo
require_once __DIR__ . '/funciones.php';
require_once __DIR__ . '/sesion.php';

if (!isset($titulo)) {
    $titulo = 'Librería Páginas del Sur';
}
$logueado = hay_sesion();
$aviso = tomar_aviso();
$pagina = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($titulo) ?> | Web SPA - Proyecto Final</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/estilos.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
  <header class="encabezado encabezado-chico">
    <div class="container">
      <h1>📚 Librería Páginas del Sur</h1>
    </div>
  </header>

  <nav class="navbar navbar-expand-lg navbar-dark navbar-libreria sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.html">Páginas del Sur</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#menuPrincipal" aria-controls="menuPrincipal"
              aria-expanded="false" aria-label="Mostrar menú">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menuPrincipal">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link" href="index.html">Inicio</a></li>
          <li class="nav-item"><a class="nav-link <?= $pagina === 'catalogo.php' ? 'active' : '' ?>" href="catalogo.php">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="catalogo.php#novedades">Novedades</a></li>
          <?php if ($logueado): ?>
            <li class="nav-item"><a class="nav-link <?= $pagina === 'carrito.php' ? 'active' : '' ?>" href="carrito.php">Mi carrito</a></li>
            <li class="nav-item"><a class="nav-link <?= $pagina === 'pedidos.php' ? 'active' : '' ?>" href="pedidos.php">Mis pedidos</a></li>
            <li class="nav-item"><a class="nav-link <?= $pagina === 'registro_libro.php' ? 'active' : '' ?>" href="registro_libro.php">Registrar libro</a></li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav align-items-lg-center">
          <?php if ($logueado): ?>
            <li class="nav-item"><span class="navbar-text me-lg-3">Hola, <?= h($_SESSION['usuario_nombre']) ?></span></li>
            <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="logout.php">Cerrar sesión</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.html">Iniciar sesión</a></li>
            <li class="nav-item"><a class="btn btn-acento ms-lg-2" href="registro_usuario.php">Registrarse</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 flex-grow-1">
    <?php if ($aviso): ?>
      <div class="alert alert-<?= h($aviso[0]) ?> alert-dismissible fade show" role="alert">
        <?= h($aviso[1]) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
      </div>
    <?php endif; ?>
