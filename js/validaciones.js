// funciones de validacion que se usan en el login y en los registros

// revisa que el correo tenga un formato valido
function validarEmail(correo) {
  const patron = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
  return patron.test(correo.trim());
}

// revisa cada requisito de la contraseña
function revisarPassword(clave) {
  return {
    largo: clave.length >= 8,
    mayuscula: /[A-Z]/.test(clave),
    minuscula: /[a-z]/.test(clave),
    numero: /[0-9]/.test(clave),
    simbolo: /[^A-Za-z0-9]/.test(clave)
  };
}

// cuenta cuantos requisitos se cumplen (de 0 a 5)
const puntajePassword = (clave) => {
  const requisitos = revisarPassword(clave);
  return Object.values(requisitos).filter(Boolean).length;
};

// la contraseña es segura solo si cumple los 5 requisitos
function passwordSegura(clave) {
  return puntajePassword(clave) === 5;
}

// telefono de celular chileno: 9 digitos, con +56 opcional
function validarTelefono(fono) {
  const limpio = fono.replace(/\s/g, '');
  return /^(\+?56)?9[0-9]{8}$/.test(limpio);
}

// texto con un minimo de caracteres
function textoMinimo(valor, minimo) {
  return valor.trim().length >= minimo;
}

// numero entero mayor o igual a un minimo
function enteroMinimo(valor, minimo) {
  return valor !== '' && Number.isInteger(Number(valor)) && Number(valor) >= minimo;
}
