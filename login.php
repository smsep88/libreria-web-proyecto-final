<?php
// procesa el inicio de sesion
require_once __DIR__ . '/includes/sesion.php';
require_once __DIR__ . '/includes/conexion.php';

// si entran directo a la url, se manda al formulario
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$email = trim($_POST['email'] ?? '');
$clave = $_POST['password'] ?? '';

// se valida de nuevo en el servidor
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $clave === '') {
    header('Location: login.html?msg=error');
    exit;
}

// buscar al usuario por su correo
$usuario = ejecutar($conexion,
    'SELECT id, nombre, contrasena FROM usuarios WHERE email = ?',
    's', [$email])->get_result()->fetch_assoc();

// se compara la clave escrita con la clave cifrada de la base de datos
if ($usuario && password_verify($clave, $usuario['contrasena'])) {
    abrir_sesion($usuario['id'], $usuario['nombre']);
    poner_aviso('success', 'Bienvenido/a, ' . $usuario['nombre'] . '.');
    header('Location: catalogo.php');
} else {
    header('Location: login.html?msg=error');
}
exit;
