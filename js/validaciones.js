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
