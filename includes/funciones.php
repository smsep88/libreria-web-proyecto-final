<?php
// funciones de apoyo para las paginas php

// escapa el texto antes de mostrarlo en html
function h($texto) {
    return htmlspecialchars((string) $texto, ENT_QUOTES, 'UTF-8');
}

// muestra un numero como pesos chilenos
function peso($numero) {
    return '$' . number_format((float) $numero, 0, ',', '.');
}

// clave segura: 8 o mas caracteres, mayuscula, minuscula, numero y simbolo
function clave_segura($clave) {
    return strlen($clave) >= 8
        && preg_match('/[A-Z]/', $clave)
        && preg_match('/[a-z]/', $clave)
        && preg_match('/[0-9]/', $clave)
        && preg_match('/[^A-Za-z0-9]/', $clave);
}

// celular chileno, con o sin +56
function telefono_valido($fono) {
    $limpio = str_replace(' ', '', $fono);
    return preg_match('/^(\+?56)?9[0-9]{8}$/', $limpio) === 1;
}
