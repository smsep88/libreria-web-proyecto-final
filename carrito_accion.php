<?php
// procesa las acciones del carrito: agregar, actualizar, eliminar, vaciar y pedir
require_once __DIR__ . '/includes/sesion.php';
require_once __DIR__ . '/includes/conexion.php';
require_once __DIR__ . '/includes/funciones.php';

exigir_login();

// solo se aceptan datos por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: carrito.php');
    exit;
}

// el usuario sale de la sesion, no del formulario
$usuario_id = (int) $_SESSION['usuario_id'];
$accion = $_POST['accion'] ?? '';
$libro_id = (int) ($_POST['libro_id'] ?? 0);
$carrito_id = (int) ($_POST['carrito_id'] ?? 0);
$cantidad = (int) ($_POST['cantidad'] ?? 1);

// ---- agregar un libro ----
if ($accion === 'agregar') {
    $libro = ejecutar($conexion, 'SELECT id, titulo, precio, inventario FROM libros WHERE id = ?',
        'i', [$libro_id])->get_result()->fetch_assoc();

    if (!$libro || $cantidad < 1) {
        poner_aviso('danger', 'Los datos del libro no son válidos.');
    } else {
        // ver si el libro ya estaba en el carrito
        $fila = ejecutar($conexion, 'SELECT id, cantidad FROM carrito WHERE usuario_id = ? AND libro_id = ?',
            'ii', [$usuario_id, $libro_id])->get_result()->fetch_assoc();
        $nueva = $cantidad + ($fila ? $fila['cantidad'] : 0);

        if ($nueva > $libro['inventario']) {
            poner_aviso('warning', 'No hay stock suficiente de "' . $libro['titulo'] . '". Quedan ' . $libro['inventario'] . '.');
        } else {
            $monto = $libro['precio'] * $nueva;
            if ($fila) {
                ejecutar($conexion, 'UPDATE carrito SET cantidad = ?, monto_total = ? WHERE id = ?',
                    'iii', [$nueva, $monto, $fila['id']]);
            } else {
                ejecutar($conexion, 'INSERT INTO carrito (usuario_id, libro_id, cantidad, monto_total) VALUES (?, ?, ?, ?)',
                    'iiii', [$usuario_id, $libro_id, $nueva, $monto]);
            }
            poner_aviso('success', '"' . $libro['titulo'] . '" se agregó al carrito.');
        }
    }
    header('Location: carrito.php');
    exit;
}

// ---- cambiar la cantidad ----
if ($accion === 'actualizar') {
    // el JOIN con usuario_id evita tocar el carrito de otra persona
    $fila = ejecutar($conexion,
        'SELECT c.id, l.precio, l.inventario FROM carrito c
         INNER JOIN libros l ON l.id = c.libro_id
         WHERE c.id = ? AND c.usuario_id = ?',
        'ii', [$carrito_id, $usuario_id])->get_result()->fetch_assoc();

    if (!$fila) {
        poner_aviso('danger', 'No se encontró ese libro en tu carrito.');
    } elseif ($cantidad < 1 || $cantidad > $fila['inventario']) {
        poner_aviso('warning', 'La cantidad debe estar entre 1 y ' . $fila['inventario'] . '.');
    } else {
        ejecutar($conexion, 'UPDATE carrito SET cantidad = ?, monto_total = ? WHERE id = ?',
            'iii', [$cantidad, $fila['precio'] * $cantidad, $fila['id']]);
        poner_aviso('success', 'Cantidad actualizada.');
    }
    header('Location: carrito.php');
    exit;
}

// ---- eliminar un libro ----
if ($accion === 'eliminar') {
    ejecutar($conexion, 'DELETE FROM carrito WHERE id = ? AND usuario_id = ?',
        'ii', [$carrito_id, $usuario_id]);
    poner_aviso('info', 'Libro eliminado del carrito.');
    header('Location: carrito.php');
    exit;
}

// ---- vaciar el carrito ----
if ($accion === 'vaciar') {
    ejecutar($conexion, 'DELETE FROM carrito WHERE usuario_id = ?', 'i', [$usuario_id]);
    poner_aviso('info', 'El carrito quedó vacío.');
    header('Location: carrito.php');
    exit;
}

// ---- realizar el pedido ----
if ($accion === 'pedir') {
    // transaccion: o se guarda todo o no se guarda nada
    $conexion->begin_transaction();
    try {
        $items = ejecutar($conexion,
            'SELECT c.libro_id, c.cantidad, l.titulo, l.precio, l.inventario
             FROM carrito c INNER JOIN libros l ON l.id = c.libro_id
             WHERE c.usuario_id = ? FOR UPDATE',
            'i', [$usuario_id])->get_result()->fetch_all(MYSQLI_ASSOC);

        if (!$items) {
            throw new Exception('Tu carrito está vacío.');
        }

        // revisar el stock y sumar el total
        $total = 0;
        foreach ($items as $item) {
            if ($item['cantidad'] > $item['inventario']) {
                throw new Exception('No hay stock suficiente de "' . $item['titulo'] . '".');
            }
            $total += $item['precio'] * $item['cantidad'];
        }

        // guardar el pedido y su detalle, y descontar el inventario
        ejecutar($conexion, 'INSERT INTO pedidos (usuario_id, total) VALUES (?, ?)',
            'ii', [$usuario_id, $total]);
        $pedido_id = $conexion->insert_id;

        foreach ($items as $item) {
            ejecutar($conexion,
                'INSERT INTO detalle_pedido (pedido_id, libro_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)',
                'iiii', [$pedido_id, $item['libro_id'], $item['cantidad'], $item['precio']]);
            ejecutar($conexion, 'UPDATE libros SET inventario = inventario - ? WHERE id = ?',
                'ii', [$item['cantidad'], $item['libro_id']]);
        }

        // el carrito queda vacío
        ejecutar($conexion, 'DELETE FROM carrito WHERE usuario_id = ?', 'i', [$usuario_id]);
        $conexion->commit();

        poner_aviso('success', 'Pedido N° ' . $pedido_id . ' realizado por ' . peso($total) . '. ¡Gracias por tu compra!');
        header('Location: pedidos.php');
        exit;

    } catch (mysqli_sql_exception $e) {
        $conexion->rollback();
        error_log('Error al hacer el pedido: ' . $e->getMessage());
        poner_aviso('danger', 'No se pudo completar el pedido. Intenta de nuevo.');
    } catch (Exception $e) {
        $conexion->rollback();
        poner_aviso('warning', $e->getMessage());
    }
    header('Location: carrito.php');
    exit;
}

// si la accion no existe
header('Location: carrito.php');
exit;
