<?php
// funciones para manejar la sesion del usuario

// tiempo maximo sin actividad (30 minutos)
define('TIEMPO_MAXIMO', 1800);

// inicia la sesion solo si no estaba iniciada
function iniciar_sesion() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1'); // la cookie no se lee desde js
        session_start();
    }
}

// guarda los datos del usuario cuando ingresa
function abrir_sesion($id, $nombre) {
    iniciar_sesion();
    session_regenerate_id(true); // id nuevo para evitar robo de sesion
    $_SESSION['usuario_id'] = $id;
    $_SESSION['usuario_nombre'] = $nombre;
    $_SESSION['inicio'] = time();
    $_SESSION['ultimo'] = time();
}

// revisa si hay un usuario con sesion activa
function hay_sesion() {
    iniciar_sesion();
    if (!isset($_SESSION['usuario_id'])) {
        return false;
    }
    // si pasó mucho tiempo sin usar el sitio se cierra
    if (time() - $_SESSION['ultimo'] > TIEMPO_MAXIMO) {
        cerrar_sesion();
        return false;
    }
    $_SESSION['ultimo'] = time();
    return true;
}

// manda al login si no hay sesion
function exigir_login($destino = 'login.html?msg=sesion') {
    if (!hay_sesion()) {
        header('Location: ' . $destino);
        exit;
    }
}

// cierra la sesion y borra la cookie
function cerrar_sesion() {
    iniciar_sesion();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// avisos que se muestran en la pagina siguiente
function poner_aviso($tipo, $texto) {
    iniciar_sesion();
    $_SESSION['aviso'] = [$tipo, $texto];
}

function tomar_aviso() {
    iniciar_sesion();
    if (!isset($_SESSION['aviso'])) {
        return null;
    }
    $aviso = $_SESSION['aviso'];
    unset($_SESSION['aviso']);
    return $aviso;
}
