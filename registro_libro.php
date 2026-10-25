<?php
// registro de libros (etapa 3)
// por ahora solo recibe los datos del formulario y los muestra
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Registro de libros';
$datos = [];
$errores = [];
$registrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recuperar los datos enviados por POST
    $datos['titulo']     = trim($_POST['titulo'] ?? '');
    $datos['autor']      = trim($_POST['autor'] ?? '');
    $datos['precio']     = $_POST['precio'] ?? '';
    $datos['inventario'] = $_POST['inventario'] ?? '';

    // validaciones en el servidor
    if (strlen($datos['titulo']) < 2) {
        $errores[] = 'Escribe el título del libro.';
    }
    if (strlen($datos['autor']) < 3) {
        $errores[] = 'Escribe el nombre del autor.';
    }
    if (!ctype_digit((string) $datos['precio']) || (int) $datos['precio'] < 1) {
        $errores[] = 'El precio debe ser un número entero mayor a 0.';
    }
    if (!ctype_digit((string) $datos['inventario'])) {
        $errores[] = 'El inventario debe ser un número entero (0 o más).';
    }

    if (!$errores) {
        $registrado = true;
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="caja-formulario caja-ancha">
  <h2 class="h4 mb-3">Registro de libros</h2>

  <?php if ($errores): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errores as $error): ?>
          <li><?= h($error) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($registrado): ?>
    <div class="alert alert-success">Datos del libro recibidos correctamente.</div>
    <table class="table table-bordered">
      <tr><th>Título</th><td><?= h($datos['titulo']) ?></td></tr>
      <tr><th>Autor</th><td><?= h($datos['autor']) ?></td></tr>
      <tr><th>Precio</th><td><?= peso($datos['precio']) ?></td></tr>
      <tr><th>Inventario</th><td><?= (int) $datos['inventario'] ?> unidades</td></tr>
    </table>
    <a href="registro_libro.php" class="btn btn-outline-secondary">Registrar otro libro</a>
  <?php else: ?>
    <form id="formLibro" action="registro_libro.php" method="post" novalidate>
      <div class="mb-3">
        <label for="titulo" class="form-label">Título</label>
        <input type="text" class="form-control" id="titulo" name="titulo"
               value="<?= h($datos['titulo'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe el título del libro.</div>
      </div>
      <div class="mb-3">
        <label for="autor" class="form-label">Autor</label>
        <input type="text" class="form-control" id="autor" name="autor"
               value="<?= h($datos['autor'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe el nombre del autor.</div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="precio" class="form-label">Precio (CLP)</label>
          <input type="number" class="form-control" id="precio" name="precio" min="1" step="1"
                 value="<?= h($datos['precio'] ?? '') ?>" required>
          <div class="invalid-feedback">El precio debe ser un entero mayor a 0.</div>
        </div>
        <div class="col-md-6 mb-3">
          <label for="inventario" class="form-label">Cantidad en inventario</label>
          <input type="number" class="form-control" id="inventario" name="inventario" min="0" step="1"
                 value="<?= h($datos['inventario'] ?? '') ?>" required>
          <div class="invalid-feedback">Escribe un entero (0 o más).</div>
        </div>
      </div>
      <button type="submit" class="btn btn-acento w-100">Registrar libro</button>
    </form>
  <?php endif; ?>
</div>

<?php
$scripts = ['js/registro.js'];
require __DIR__ . '/includes/footer.php';
