<?php
// registro de usuarios (etapa 3)
// por ahora solo recibe los datos del formulario y los muestra
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Registro de usuarios';
$datos = [];
$errores = [];
$registrado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // recuperar los datos enviados por POST
    $datos['nombre']    = trim($_POST['nombre'] ?? '');
    $datos['email']     = trim($_POST['email'] ?? '');
    $datos['direccion'] = trim($_POST['direccion'] ?? '');
    $datos['telefono']  = trim($_POST['telefono'] ?? '');
    $clave  = $_POST['password'] ?? '';
    $clave2 = $_POST['password2'] ?? '';

    // validaciones en el servidor
    if (strlen($datos['nombre']) < 3) {
        $errores[] = 'El nombre debe tener al menos 3 caracteres.';
    }
    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo no tiene un formato válido.';
    }
    if (!clave_segura($clave)) {
        $errores[] = 'La contraseña debe tener 8 caracteres, mayúscula, minúscula, número y símbolo.';
    }
    if ($clave !== $clave2) {
        $errores[] = 'Las contraseñas no coinciden.';
    }
    if (strlen($datos['direccion']) < 5) {
        $errores[] = 'Escribe una dirección de envío.';
    }
    if (!telefono_valido($datos['telefono'])) {
        $errores[] = 'El teléfono debe ser un celular chileno (ej: +56 9 1234 5678).';
    }

    // si no hay errores, los datos quedan listos para guardarse
    if (!$errores) {
        $registrado = true;
        $datos['largo_clave'] = strlen($clave);
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="caja-formulario caja-ancha">
  <h2 class="h4 mb-3">Registro de usuarios</h2>

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
    <!-- datos que llegaron al script -->
    <div class="alert alert-success">Datos recibidos correctamente.</div>
    <table class="table table-bordered">
      <tr><th>Nombre</th><td><?= h($datos['nombre']) ?></td></tr>
      <tr><th>Correo</th><td><?= h($datos['email']) ?></td></tr>
      <tr><th>Contraseña</th><td><?= str_repeat('•', $datos['largo_clave']) ?></td></tr>
      <tr><th>Dirección</th><td><?= h($datos['direccion']) ?></td></tr>
      <tr><th>Teléfono</th><td><?= h($datos['telefono']) ?></td></tr>
    </table>
    <a href="registro_usuario.php" class="btn btn-outline-secondary">Registrar otro usuario</a>
  <?php else: ?>
    <form id="formRegistro" action="registro_usuario.php" method="post" novalidate>
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre completo</label>
        <input type="text" class="form-control" id="nombre" name="nombre"
               value="<?= h($datos['nombre'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe tu nombre (mínimo 3 letras).</div>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" id="email" name="email"
               value="<?= h($datos['email'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe un correo válido.</div>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" required>
          <div class="invalid-feedback">Mínimo 8 caracteres con mayúscula, minúscula, número y símbolo.</div>
        </div>
        <div class="col-md-6 mb-3">
          <label for="password2" class="form-label">Repetir contraseña</label>
          <input type="password" class="form-control" id="password2" name="password2" required>
          <div class="invalid-feedback">Las contraseñas no coinciden.</div>
        </div>
      </div>
      <div class="mb-3">
        <label for="direccion" class="form-label">Dirección de envío</label>
        <input type="text" class="form-control" id="direccion" name="direccion"
               value="<?= h($datos['direccion'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe una dirección (mínimo 5 caracteres).</div>
      </div>
      <div class="mb-3">
        <label for="telefono" class="form-label">Teléfono</label>
        <input type="text" class="form-control" id="telefono" name="telefono"
               placeholder="+56 9 1234 5678" value="<?= h($datos['telefono'] ?? '') ?>" required>
        <div class="invalid-feedback">Escribe un celular chileno, por ejemplo +56 9 1234 5678.</div>
      </div>
      <button type="submit" class="btn btn-acento w-100">Crear cuenta</button>
    </form>
  <?php endif; ?>
</div>

<?php
$scripts = ['js/registro.js'];
require __DIR__ . '/includes/footer.php';
