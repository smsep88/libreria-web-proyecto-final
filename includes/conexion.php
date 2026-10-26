<?php
// conexion a MySQL con mysqli
// si en tu equipo el usuario o la clave son otros, se cambian aqui
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '12345');
define('DB_NAME', 'LIBRERIA');
define('DB_PORT', 3306);

// mysqli lanza excepciones cuando hay un error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexion = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $conexion->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // el detalle del error no se muestra al usuario, queda en el log de php
    error_log('Error de conexion: ' . $e->getMessage());
    http_response_code(500);
    die('No se pudo conectar con la base de datos.');
}

// ejecuta una consulta preparada (los datos van separados del sql)
// $tipos: i = entero, s = texto. Ejemplo: 'is'
function ejecutar($conexion, $sql, $tipos = '', $parametros = []) {
    $stmt = $conexion->prepare($sql);
    if ($tipos !== '') {
        $stmt->bind_param($tipos, ...$parametros);
    }
    $stmt->execute();
    return $stmt;
}
