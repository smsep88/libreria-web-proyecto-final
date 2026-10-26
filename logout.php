<?php
// cierra la sesion del usuario
require_once __DIR__ . '/includes/sesion.php';

cerrar_sesion();
header('Location: login.html?msg=salida');
exit;
