<?php
// prototipo de sesiones: login de prueba y carrito guardado en $_SESSION
require_once __DIR__ . '/../includes/sesion.php';
require_once __DIR__ . '/../includes/funciones.php';
iniciar_sesion();

// usuario de prueba (en la etapa 5 se cambia por la base de datos)
$email_demo = 'demo@libreria.cl';
$clave_demo = 'Demo1234!';

// libros de ejemplo
$libros = [
    1 => ['titulo' => 'Cien años de soledad',     'autor' => 'Gabriel García Márquez', 'precio' => 12990],
    2 => ['titulo' => 'La casa de los espíritus', 'autor' => 'Isabel Allende',         'precio' => 11990],
    3 => ['titulo' => 'Pedro Páramo',             'autor' => 'Juan Rulfo',             'precio' => 8990],
    4 => ['titulo' => 'Ficciones',                'autor' => 'Jorge Luis Borges',      'precio' => 9990],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = (int) ($_POST['libro_id'] ?? 0);

    if ($accion === 'login') {
        $email = trim($_POST['email'] ?? '');
        $clave = $_POST['password'] ?? '';
        if ($email === $email_demo && $clave === $clave_demo) {
            abrir_sesion(1, 'Usuario Demo');
            $_SESSION['carrito'] = [];
            poner_aviso('success', 'Sesión iniciada. Ya puedes usar el carrito.');
        } else {
            poner_aviso('danger', 'Correo o contraseña incorrectos.');
        }
    } elseif (hay_sesion()) {
        // el resto de las acciones solo funcionan con sesion
        if ($accion === 'agregar' && isset($libros[$id])) {
            $_SESSION['carrito'][$id] = ($_SESSION['carrito'][$id] ?? 0) + 1;
        } elseif ($accion === 'quitar') {
            unset($_SESSION['carrito'][$id]);
        } elseif ($accion === 'vaciar') {
            $_SESSION['carrito'] = [];
        } elseif ($accion === 'salir') {
            cerrar_sesion();
            // el aviso va por la url porque la sesion ya se destruyo
            header('Location: prototipo_sesion.php?msg=salida');
            exit;
        }
    }

    // se redirige para que F5 no repita el POST
    header('Location: prototipo_sesion.php');
    exit;
}

$aviso = tomar_aviso();
if (!$aviso && ($_GET['msg'] ?? '') === 'salida') {
    $aviso = ['info', 'Cerraste sesión. El carrito se borró.'];
}
$logueado = hay_sesion();
$carrito = $logueado ? ($_SESSION['carrito'] ?? []) : [];
$total = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prototipo de sesiones | Web SPA - Proyecto Final</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/estilos.css" rel="stylesheet">
</head>
<body>
  <header class="encabezado encabezado-chico">
    <div class="container"><h1>📚 Prototipo de sesiones y carrito</h1></div>
  </header>

  <main class="container py-4">
    <?php if ($aviso): ?>
      <div class="alert alert-<?= h($aviso[0]) ?>"><?= h($aviso[1]) ?></div>
    <?php endif; ?>

    <?php if (!$logueado): ?>
      <!-- sin sesion solo se ve el login -->
      <div class="caja-formulario">
        <h2 class="h4 text-center mb-3">Acceso restringido</h2>
        <p class="text-muted small">Datos de prueba: <strong>demo@libreria.cl</strong> / <strong>Demo1234!</strong></p>
        <form method="post" action="prototipo_sesion.php">
          <input type="hidden" name="accion" value="login">
          <div class="mb-3">
            <label class="form-label" for="email">Correo</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <button class="btn btn-acento w-100">Ingresar</button>
        </form>
      </div>
    <?php else: ?>
      <div class="d-flex justify-content-between align-items-center mb-4">
        <span>Sesión de <strong><?= h($_SESSION['usuario_nombre']) ?></strong></span>
        <div class="d-flex gap-2">
          <a class="btn btn-outline-dark btn-sm" href="zona_privada.php">Zona privada</a>
          <form method="post" class="m-0">
            <input type="hidden" name="accion" value="salir">
            <button class="btn btn-outline-danger btn-sm">Cerrar sesión</button>
          </form>
        </div>
      </div>

      <div class="row g-4">
        <!-- libros disponibles -->
        <div class="col-lg-6">
          <h2 class="h5">Libros</h2>
          <?php foreach ($libros as $id => $libro): ?>
            <div class="card tarjeta-libro shadow-sm mb-3">
              <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= h($libro['titulo']) ?></strong><br>
                  <small class="text-muted"><?= h($libro['autor']) ?> - <?= peso($libro['precio']) ?></small>
                </div>
                <form method="post" class="m-0">
                  <input type="hidden" name="accion" value="agregar">
                  <input type="hidden" name="libro_id" value="<?= $id ?>">
                  <button class="btn btn-acento btn-sm">Agregar</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <!-- carrito guardado en la sesion -->
        <div class="col-lg-6">
          <h2 class="h5">Carrito (guardado en la sesión)</h2>
          <?php if (!$carrito): ?>
            <div class="alert alert-info">El carrito está vacío.</div>
          <?php else: ?>
            <table class="table bg-white align-middle">
              <thead><tr><th>Libro</th><th>Cant.</th><th>Subtotal</th><th></th></tr></thead>
              <tbody>
              <?php foreach ($carrito as $id => $cantidad):
                  $subtotal = $libros[$id]['precio'] * $cantidad;
                  $total += $subtotal; ?>
                <tr>
                  <td><?= h($libros[$id]['titulo']) ?></td>
                  <td><?= $cantidad ?></td>
                  <td><?= peso($subtotal) ?></td>
                  <td>
                    <form method="post" class="m-0">
                      <input type="hidden" name="accion" value="quitar">
                      <input type="hidden" name="libro_id" value="<?= $id ?>">
                      <button class="btn btn-outline-danger btn-sm">Quitar</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
              <tfoot><tr><th colspan="2">Total</th><th colspan="2"><?= peso($total) ?></th></tr></tfoot>
            </table>
            <form method="post">
              <input type="hidden" name="accion" value="vaciar">
              <button class="btn btn-outline-secondary btn-sm">Vaciar carrito</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
