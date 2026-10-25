<?php
// parte de arriba de las paginas php (head, encabezado y menu)
// la variable $titulo se define antes de incluir este archivo
require_once __DIR__ . '/funciones.php';

if (!isset($titulo)) {
    $titulo = 'Librería Páginas del Sur';
}
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
          <li class="nav-item"><a class="nav-link" href="catalogo.php">Catálogo</a></li>
          <li class="nav-item"><a class="nav-link" href="registro_libro.php">Registrar libro</a></li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="login.html">Iniciar sesión</a></li>
          <li class="nav-item"><a class="btn btn-acento ms-lg-2" href="registro_usuario.php">Registrarse</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <main class="container py-4 flex-grow-1">
