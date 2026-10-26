<?php
// catalogo: novedades y todos los libros disponibles
require_once __DIR__ . '/includes/sesion.php';
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

$titulo = 'Catálogo';

// las novedades son los ultimos 4 libros registrados
$novedades = $conexion->query('SELECT id, titulo, autor, precio, inventario FROM libros ORDER BY id DESC LIMIT 4')
                      ->fetch_all(MYSQLI_ASSOC);
$libros = $conexion->query('SELECT id, titulo, autor, precio, inventario FROM libros ORDER BY titulo')
                   ->fetch_all(MYSQLI_ASSOC);

// dibuja la tarjeta de un libro
function tarjeta_libro($libro, $logueado) {
    ?>
    <div class="col-sm-6 col-lg-3">
      <div class="card tarjeta-libro h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="portada-libro mb-3">📕</div>
          <h3 class="h6"><?= h($libro['titulo']) ?></h3>
          <p class="text-muted small mb-2"><?= h($libro['autor']) ?></p>
          <p class="precio mb-1"><?= peso($libro['precio']) ?></p>
          <p class="small mb-3">
            <?php if ($libro['inventario'] > 0): ?>
              <span class="badge bg-success">Disponible: <?= $libro['inventario'] ?></span>
            <?php else: ?>
              <span class="badge bg-secondary">Agotado</span>
            <?php endif; ?>
          </p>
          <div class="mt-auto">
            <?php if ($libro['inventario'] == 0): ?>
              <button class="btn btn-outline-secondary w-100" disabled>Sin stock</button>
            <?php elseif ($logueado): ?>
              <form action="carrito_accion.php" method="post">
                <input type="hidden" name="accion" value="agregar">
                <input type="hidden" name="libro_id" value="<?= $libro['id'] ?>">
                <input type="hidden" name="cantidad" value="1">
                <button class="btn btn-acento w-100">Agregar al carrito</button>
              </form>
            <?php else: ?>
              <a href="login.html?msg=sesion" class="btn btn-outline-dark w-100">Inicia sesión para comprar</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php
}

require __DIR__ . '/includes/header.php';
?>

<section id="novedades" class="mb-5">
  <h2 class="mb-3">✨ Novedades</h2>
  <div class="row g-4">
    <?php foreach ($novedades as $libro) { tarjeta_libro($libro, $logueado); } ?>
  </div>
</section>

<section id="titulos">
  <h2 class="mb-3">Todos los títulos (<?= count($libros) ?>)</h2>
  <div class="row g-4">
    <?php foreach ($libros as $libro) { tarjeta_libro($libro, $logueado); } ?>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php';
