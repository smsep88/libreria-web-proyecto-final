// muestra avisos en el login segun el parametro msg de la url
const avisos = {
  error: ['danger', 'Correo o contraseña incorrectos.'],
  registrado: ['success', 'Tu cuenta fue creada. Ya puedes iniciar sesión.'],
  sesion: ['warning', 'Debes iniciar sesión para continuar.'],
  salida: ['info', 'Cerraste sesión correctamente.']
};

const msg = new URLSearchParams(window.location.search).get('msg');
const contenedorAviso = document.getElementById('avisoLogin');

if (msg && avisos[msg] && contenedorAviso) {
  contenedorAviso.innerHTML =
    '<div class="alert alert-' + avisos[msg][0] + '" role="alert">' + avisos[msg][1] + '</div>';
}
