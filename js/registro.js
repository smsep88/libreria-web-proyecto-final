// validacion de los formularios de registro (usuario y libro)
const formRegistro = document.getElementById('formRegistro');
const formLibro = document.getElementById('formLibro');

// pone el campo en verde o rojo
function marcar(campo, esValido) {
  campo.classList.toggle('is-valid', esValido);
  campo.classList.toggle('is-invalid', !esValido);
}

// revisa cada campo mientras se escribe y todo junto al enviar
function activarValidacion(formulario, reglas) {
  const nombres = Object.keys(reglas);

  nombres.forEach(function (nombre) {
    const campo = formulario.elements[nombre];
    campo.addEventListener('input', function () {
      marcar(campo, reglas[nombre](campo.value));
    });
  });

  formulario.addEventListener('submit', function (e) {
    let todoOk = true;
    nombres.forEach(function (nombre) {
      const campo = formulario.elements[nombre];
      const ok = reglas[nombre](campo.value);
      marcar(campo, ok);
      if (!ok) {
        todoOk = false;
      }
    });
    if (!todoOk) {
      e.preventDefault();
    }
  });
}

if (formRegistro) {
  activarValidacion(formRegistro, {
    nombre: (v) => textoMinimo(v, 3),
    email: validarEmail,
    password: passwordSegura,
    password2: (v) => v !== '' && v === formRegistro.elements['password'].value,
    direccion: (v) => textoMinimo(v, 5),
    telefono: validarTelefono
  });

  // si cambia la clave, se revisa de nuevo la repeticion
  formRegistro.elements['password'].addEventListener('input', function () {
    const repetida = formRegistro.elements['password2'];
    if (repetida.value !== '') {
      marcar(repetida, repetida.value === this.value);
    }
  });
}

if (formLibro) {
  activarValidacion(formLibro, {
    titulo: (v) => textoMinimo(v, 2),
    autor: (v) => textoMinimo(v, 3),
    precio: (v) => enteroMinimo(v, 1),
    inventario: (v) => enteroMinimo(v, 0)
  });
}
