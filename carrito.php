<?php
// pantalla del carrito de compras
require_once __DIR__ . '/includes/sesion.php';
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

exigir_login();
$titulo = 'Mi carrito';
$usuario_id = (int) $_SESSION['usuario_id'];

// libros para el select del formulario
$libros = $conexion->query('SELECT id, titulo, precio, inventario FROM libros ORDER BY titulo')
                   ->fetch_all(MYSQLI_ASSOC);

// lo que tiene el usuario en su carrito
$items = ejecutar($conexion,
    'SELECT c.id, c.cantidad, c.monto_total, l.titulo, l.autor, l.precio, l.inventario
     FROM carrito c INNER JOIN libros l ON l.id = c.libro_id
     WHERE c.usuario_id = ? ORDER BY c.id',
    'i', [$usuario_id])->get_result()->fetch_all(MYSQLI_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total += $item['monto_total'];
}

$scripts = ['js/carrito.js'];
require __DIR__ . '/includes/header.php';
?>

<h2 class="mb-4">🛒 Mi carrito</h2>

<!-- formulario para agregar un libro -->
<div class="card shadow-sm mb-4">
  <div class="card-body">
    <h3 class="h5 mb-3">Agregar un libro</h3>
    <form id="formAgregar" action="carrito_accion.php" method="post" novalidate>
      <input type="hidden" name="accion" value="agregar">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="libro_id" class="form-label">Libro</label>
          <select class="form-select" id="libro_id" name="libro_id" required>
            <option value="" data-precio="0" data-stock="0">Selecciona un libro...</option>
            <?php foreach ($libros as $libro): ?>
              <option value="<?= $libro['id'] ?>"
                      data-precio="<?= $libro['precio'] ?>"
                      data-stock="<?= $libro['inventario'] ?>"
                      <?= $libro['inventario'] == 0 ? 'disabled' : '' ?>>
                <?= h($libro['titulo']) ?> - <?= peso($libro['precio']) ?> (stock: <?= $libro['inventario'] ?>)
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Elige un libro.</div>
        </div>
        <div class="col-md-6">
          <label for="cliente" class="form-label">Pedido a nombre de</label>
          <input type="text" class="form-control" id="cliente"
                 value="<?= h($_SESSION['usuario_nombre']) ?>" readonly>
        </div>
        <div class="col-md-3">
          <label for="cantidad" class="form-label">Cantidad</label>
          <input type="number" class="form-control" id="cantidad" name="cantidad" min="1" value="1" required>
          <div class="invalid-feedback" id="errorCantidad">Cantidad no válida.</div>
        </div>
        <div class="col-md-3">
          <label for="monto" class="form-label">Monto total</label>
          <input type="text" class="form-control" id="monto" value="$0" readonly>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <button type="submit" class="btn btn-acento w-100">Agregar al carrito</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- libros que ya estan en el carrito -->
<?php if (!$items): ?>
  <div class="alert alert-info">Tu carrito está vacío. Agrega un libro con el formulario o desde el
    <a href="catalogo.php" class="alert-link">catálogo</a>.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table table-hover align-middle bg-white">
      <thead class="table-dark">
        <tr>
          <th>Libro</th>
          <th>Precio</th>
          <th class="col-cantidad">Cantidad</th>
          <th>Monto</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td>
              <strong><?= h($item['titulo']) ?></strong><br>
              <small class="text-muted"><?= h($item['autor']) ?></small>
            </td>
            <td><?= peso($item['precio']) ?></td>
            <td>
              <!-- modificar la cantidad -->
              <form action="carrito_accion.php" method="post" class="d-flex gap-2">
                <input type="hidden" name="accion" value="actualizar">
                <input type="hidden" name="carrito_id" value="<?= $item['id'] ?>">
                <input type="number" name="cantidad" class="form-control form-control-sm"
                       min="1" max="<?= $item['inventario'] ?>" value="<?= $item['cantidad'] ?>" required>
                <button class="btn btn-sm btn-outline-primary">Actualizar</button>
              </form>
            </td>
            <td><?= peso($item['monto_total']) ?></td>
            <td>
              <!-- eliminar el libro del carrito -->
              <form action="carrito_accion.php" method="post" data-confirmar="¿Quitar este libro del carrito?">
                <input type="hidden" name="accion" value="eliminar">
                <input type="hidden" name="carrito_id" value="<?= $item['id'] ?>">
                <button class="btn btn-sm btn-outline-danger">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Total a pagar</th>
          <th colspan="2" class="fs-5"><?= peso($total) ?></th>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="d-flex flex-wrap gap-2 justify-content-end">
    <form action="carrito_accion.php" method="post" data-confirmar="¿Vaciar todo el carrito?">
      <input type="hidden" name="accion" value="vaciar">
      <button class="btn btn-outline-secondary">Vaciar carrito</button>
    </form>
    <form action="carrito_accion.php" method="post"
          data-confirmar="¿Confirmas el pedido por <?= peso($total) ?>?">
      <input type="hidden" name="accion" value="pedir">
      <button class="btn btn-acento">Realizar pedido</button>
    </form>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php';
