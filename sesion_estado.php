<?php
// responde en json si hay sesion (lo usa js/nav.js en las paginas html)
require_once __DIR__ . '/includes/sesion.php';

header('Content-Type: application/json; charset=utf-8');

if (hay_sesion()) {
    echo json_encode(['logueado' => true, 'nombre' => $_SESSION['usuario_nombre']]);
} else {
    echo json_encode(['logueado' => false]);
}
