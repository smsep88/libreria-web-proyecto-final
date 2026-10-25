// validacion en tiempo real del formulario de login
const formLogin = document.getElementById('formLogin');
const campoEmail = document.getElementById('email');
const campoClave = document.getElementById('password');
const barraClave = document.getElementById('barraClave');
const textoNivel = document.getElementById('textoNivel');
const botonVer = document.getElementById('verClave');

const niveles = ['vacía', 'muy débil', 'débil', 'regular', 'buena', 'segura'];

// marca el campo en verde o rojo (clases de bootstrap)
function marcarCampo(campo, esValido) {
  campo.classList.toggle('is-valid', esValido);
  campo.classList.toggle('is-invalid', !esValido);
}

// actualiza la lista de requisitos y la barra de seguridad
function actualizarRequisitos() {
  const requisitos = revisarPassword(campoClave.value);
  for (const nombre in requisitos) {
    const item = document.querySelector('[data-req="' + nombre + '"]');
    item.classList.toggle('cumple', requisitos[nombre]);
  }

  const puntaje = puntajePassword(campoClave.value);
  barraClave.style.width = (puntaje * 20) + '%';
  let color = 'bg-success';
  if (puntaje <= 2) {
    color = 'bg-danger';
  } else if (puntaje < 5) {
    color = 'bg-warning';
  }
  barraClave.className = 'progress-bar ' + color;
  textoNivel.textContent = 'Seguridad: ' + niveles[puntaje];
}

// eventos mientras el usuario escribe
campoEmail.addEventListener('input', () => {
  marcarCampo(campoEmail, validarEmail(campoEmail.value));
});

campoClave.addEventListener('input', () => {
  actualizarRequisitos();
  marcarCampo(campoClave, passwordSegura(campoClave.value));
});

// boton para ver u ocultar la contraseña
botonVer.addEventListener('click', function () {
  const estaOculta = campoClave.type === 'password';
  campoClave.type = estaOculta ? 'text' : 'password';
  botonVer.textContent = estaOculta ? 'Ocultar' : 'Ver';
});

// al enviar se vuelve a revisar todo
formLogin.addEventListener('submit', function (e) {
  const emailOk = validarEmail(campoEmail.value);
  const claveOk = passwordSegura(campoClave.value);
  marcarCampo(campoEmail, emailOk);
  marcarCampo(campoClave, claveOk);

  if (!emailOk || !claveOk) {
    e.preventDefault(); // no se envia si hay errores
  }
});
