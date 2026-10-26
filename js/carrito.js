// validaciones y calculo del monto en la pagina del carrito
const formAgregar = document.getElementById('formAgregar');
const selectLibro = document.getElementById('libro_id');
const inputCantidad = document.getElementById('cantidad');
const inputMonto = document.getElementById('monto');
const errorCantidad = document.getElementById('errorCantidad');

// da formato de pesos chilenos
function formatoPeso(numero) {
  return '$' + numero.toLocaleString('es-CL');
}

// datos del libro elegido en el select
function libroElegido() {
  const opcion = selectLibro.options[selectLibro.selectedIndex];
  return {
    id: selectLibro.value,
    precio: Number(opcion.dataset.precio),
    stock: Number(opcion.dataset.stock)
  };
}

// calcula el monto total = precio x cantidad
function calcularMonto() {
  const libro = libroElegido();
  const cantidad = parseInt(inputCantidad.value, 10) || 0;
  inputMonto.value = formatoPeso(libro.precio * cantidad);
}

// revisa libro y cantidad, y deja el mensaje de error que corresponda
function validarAgregar() {
  const libro = libroElegido();
  const cantidad = Number(inputCantidad.value);
  const libroOk = libro.id !== '';
  const enteroOk = Number.isInteger(cantidad) && cantidad >= 1;
  const stockOk = libroOk && cantidad <= libro.stock;

  if (!enteroOk) {
    errorCantidad.textContent = 'La cantidad debe ser un número entero desde 1.';
  } else if (!stockOk) {
    errorCantidad.textContent = 'Solo quedan ' + libro.stock + ' unidades de este libro.';
  }

  selectLibro.classList.toggle('is-invalid', !libroOk);
  inputCantidad.classList.toggle('is-invalid', !(enteroOk && stockOk));
  return libroOk && enteroOk && stockOk;
}

if (formAgregar) {
  selectLibro.addEventListener('change', () => { calcularMonto(); validarAgregar(); });
  inputCantidad.addEventListener('input', () => { calcularMonto(); validarAgregar(); });

  formAgregar.addEventListener('submit', function (e) {
    if (!validarAgregar()) {
      e.preventDefault();
    }
  });
}

// pide confirmacion antes de eliminar, vaciar o hacer el pedido
document.querySelectorAll('form[data-confirmar]').forEach(function (formulario) {
  formulario.addEventListener('submit', function (e) {
    if (!confirm(formulario.dataset.confirmar)) {
      e.preventDefault();
    }
  });
});
