<?php
// historial de pedidos del usuario
require_once __DIR__ . '/includes/sesion.php';
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

exigir_login();
$titulo = 'Mis pedidos';
$usuario_id = (int) $_SESSION['usuario_id'];

$pedidos = ejecutar($conexion,
    'SELECT id, total, estado, fecha FROM pedidos WHERE usuario_id = ? ORDER BY id DESC',
    'i', [$usuario_id])->get_result()->fetch_all(MYSQLI_ASSOC);

require __DIR__ . '/includes/header.php';
?>

<h2 class="mb-4">📦 Mis pedidos</h2>

<?php if (!$pedidos): ?>
  <div class="alert alert-info">Todavía no has hecho pedidos. Revisa el <a href="catalogo.php" class="alert-link">catálogo</a>.</div>
<?php endif; ?>

<?php foreach ($pedidos as $pedido):
    // detalle de cada pedido
    $detalle = ejecutar($conexion,
        'SELECT d.cantidad, d.precio_unitario, l.titulo
         FROM detalle_pedido d INNER JOIN libros l ON l.id = d.libro_id
         WHERE d.pedido_id = ?',
        'i', [$pedido['id']])->get_result()->fetch_all(MYSQLI_ASSOC);
?>
  <div class="card shadow-sm mb-3">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
      <span><strong>Pedido N° <?= $pedido['id'] ?></strong> - <?= h(date('d-m-Y H:i', strtotime($pedido['fecha']))) ?></span>
      <span><span class="badge bg-primary"><?= h($pedido['estado']) ?></span></span>
    </div>
    <ul class="list-group list-group-flush">
      <?php foreach ($detalle as $linea): ?>
        <li class="list-group-item d-flex justify-content-between">
          <span><?= h($linea['titulo']) ?> x <?= $linea['cantidad'] ?></span>
          <span><?= peso($linea['precio_unitario'] * $linea['cantidad']) ?></span>
        </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between fw-bold">
        <span>Total</span><span><?= peso($pedido['total']) ?></span>
      </li>
    </ul>
  </div>
<?php endforeach; ?>

<?php require __DIR__ . '/includes/footer.php';
