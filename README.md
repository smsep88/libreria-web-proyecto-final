# Librería Páginas del Sur

Proyecto final de Programación Web II (IACC). Aplicación web full stack con carrito de compras para una librería en línea.

## Qué incluye
- Frontend con HTML, CSS y Bootstrap 5 (diseño adaptable)
- Validaciones en tiempo real con JavaScript
- Backend en PHP: formularios, sesiones y carrito
- Base de datos MySQL (`LIBRERIA`) con mysqli y consultas preparadas
- Control de versiones con Git y GitHub

## Funciones
- Registro de usuarios
- Inicio de sesión con control de sesiones
- Catálogo con novedades
- Agregar, modificar cantidad y eliminar libros del carrito
- Realizar pedidos y ver el historial

## Cómo ejecutarlo
1. Tener instalado PHP (con la extensión `mysqli`) y MySQL.
2. Crear la base de datos ejecutando `sql/libreria.sql` en MySQL Workbench.
3. Revisar usuario y clave en `includes/conexion.php` (por defecto `root` / `12345`, puerto 3306).
4. Abrir la carpeta en VS Code y levantar el servidor con la extensión **PHP Server**
   (clic derecho en `index.html` > *PHP Server: Serve project*), o desde una terminal:
   `php -S localhost:8000`
5. Entrar a `http://localhost:8000/index.html` (o al puerto que indique PHP Server).

`prueba_conexion.php` sirve para comprobar que PHP se conecta a la base de datos.

## Carpetas
- `css/`, `js/`: estilos y scripts
- `includes/`: conexión, sesión, funciones y plantillas PHP
- `sql/`: script de la base de datos
- `prototipo/`: prototipo de sesiones (etapa 4)
