// cambia el menu de las paginas html cuando hay sesion iniciada
function agregarItem(menu, texto, enlace) {
  const li = document.createElement('li');
  li.className = 'nav-item';
  const a = document.createElement('a');
  a.className = 'nav-link';
  a.href = enlace;
  a.textContent = texto;
  li.appendChild(a);
  menu.appendChild(li);
}

fetch('sesion_estado.php')
  .then((respuesta) => respuesta.json())
  .then((estado) => {
    const menu = document.getElementById('menuUsuario');
    if (!estado.logueado || !menu) {
      return;
    }
    menu.innerHTML = '';

    const saludo = document.createElement('li');
    saludo.className = 'nav-item';
    const texto = document.createElement('span');
    texto.className = 'navbar-text me-lg-3';
    texto.textContent = 'Hola, ' + estado.nombre; // textContent evita meter html
    saludo.appendChild(texto);
    menu.appendChild(saludo);

    agregarItem(menu, 'Mi carrito', 'carrito.php');
    agregarItem(menu, 'Mis pedidos', 'pedidos.php');
    agregarItem(menu, 'Cerrar sesión', 'logout.php');
  })
  .catch(() => {
    // si falla se deja el menu normal
  });
