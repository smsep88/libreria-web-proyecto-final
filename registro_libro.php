<?php
// registro de libros: valida los datos y los guarda en la tabla libros
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/sesion.php';

// solo pueden registrar libros los usuarios con sesion
exigir_login();

$titulo = 'Registro de libros';
$datos = [];
$errores = [];

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

    // guardar el libro
    if (!$errores) {
        ejecutar($conexion,
            'INSERT INTO libros (titulo, autor, precio, inventario) VALUES (?, ?, ?, ?)',
            'ssii', [$datos['titulo'], $datos['autor'], (int) $datos['precio'], (int) $datos['inventario']]);

        poner_aviso('success', 'Libro registrado con el ID ' . $conexion->insert_id . '.');
        header('Location: registro_libro.php');
        exit;
    }
}

// consulta de los libros ya registrados
$lista = $conexion->query('SELECT id, titulo, autor, precio, inventario FROM libros ORDER BY id DESC')
                  ->fetch_all(MYSQLI_ASSOC);

require __DIR__ . '/includes/header.php';
?>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="caja-formulario m-0">
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
          <div class="col-6 mb-3">
            <label for="precio" class="form-label">Precio (CLP)</label>
            <input type="number" class="form-control" id="precio" name="precio" min="1" step="1"
                   value="<?= h($datos['precio'] ?? '') ?>" required>
            <div class="invalid-feedback">Entero mayor a 0.</div>
          </div>
          <div class="col-6 mb-3">
            <label for="inventario" class="form-label">Inventario</label>
            <input type="number" class="form-control" id="inventario" name="inventario" min="0" step="1"
                   value="<?= h($datos['inventario'] ?? '') ?>" required>
            <div class="invalid-feedback">Entero (0 o más).</div>
          </div>
        </div>
        <button type="submit" class="btn btn-acento w-100">Registrar libro</button>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <h2 class="h5">Libros registrados (<?= count($lista) ?>)</h2>
    <div class="table-responsive">
      <table class="table table-sm table-striped bg-white align-middle">
        <thead class="table-dark">
          <tr><th>ID</th><th>Título</th><th>Autor</th><th>Precio</th><th>Stock</th></tr>
        </thead>
        <tbody>
          <?php foreach ($lista as $libro): ?>
            <tr>
              <td><?= $libro['id'] ?></td>
              <td><?= h($libro['titulo']) ?></td>
              <td><?= h($libro['autor']) ?></td>
              <td><?= peso($libro['precio']) ?></td>
              <td><?= $libro['inventario'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
$scripts = ['js/registro.js'];
require __DIR__ . '/includes/footer.php';
