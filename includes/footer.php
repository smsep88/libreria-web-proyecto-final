  </main>

  <footer class="pie">
    <div class="container text-center">
      <small>Proyecto final Programación Web II - IACC</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/validaciones.js"></script>
<?php
// scripts extra de cada pagina (se define $scripts antes del header)
if (!empty($scripts)) {
    foreach ($scripts as $archivo) {
        echo '  <script src="' . h($archivo) . '"></script>' . "\n";
    }
}
?>
</body>
</html>
