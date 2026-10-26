-- base de datos de la libreria en linea
-- ojo: si la base LIBRERIA ya existe, este script la borra y la crea de nuevo
DROP DATABASE IF EXISTS LIBRERIA;
CREATE DATABASE LIBRERIA CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE LIBRERIA;

-- usuarios de la tienda (la contraseña se guarda cifrada con password_hash)
CREATE TABLE usuarios (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  contrasena VARCHAR(255) NOT NULL,
  direccion VARCHAR(200) NOT NULL,
  telefono VARCHAR(20) NOT NULL
) ENGINE=InnoDB;

-- libros a la venta (precio en pesos chilenos)
CREATE TABLE libros (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(150) NOT NULL,
  autor VARCHAR(100) NOT NULL,
  precio INT UNSIGNED NOT NULL,
  inventario INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

-- carrito de cada usuario (un libro solo puede estar una vez por usuario)
CREATE TABLE carrito (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  libro_id INT UNSIGNED NOT NULL,
  cantidad INT UNSIGNED NOT NULL DEFAULT 1,
  monto_total INT UNSIGNED NOT NULL,
  UNIQUE KEY uq_usuario_libro (usuario_id, libro_id),
  CONSTRAINT fk_carrito_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
  CONSTRAINT fk_carrito_libro FOREIGN KEY (libro_id) REFERENCES libros(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- tablas extra para guardar los pedidos ya confirmados
CREATE TABLE pedidos (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT UNSIGNED NOT NULL,
  total INT UNSIGNED NOT NULL,
  estado VARCHAR(20) NOT NULL DEFAULT 'Recibido',
  fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pedido_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
) ENGINE=InnoDB;

CREATE TABLE detalle_pedido (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT UNSIGNED NOT NULL,
  libro_id INT UNSIGNED NOT NULL,
  cantidad INT UNSIGNED NOT NULL,
  precio_unitario INT UNSIGNED NOT NULL,
  CONSTRAINT fk_detalle_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  CONSTRAINT fk_detalle_libro FOREIGN KEY (libro_id) REFERENCES libros(id)
) ENGINE=InnoDB;

-- libros de ejemplo
INSERT INTO libros (titulo, autor, precio, inventario) VALUES
('Cien años de soledad', 'Gabriel García Márquez', 12990, 15),
('La casa de los espíritus', 'Isabel Allende', 11990, 12),
('Pedro Páramo', 'Juan Rulfo', 8990, 10),
('Rayuela', 'Julio Cortázar', 14990, 8),
('Ficciones', 'Jorge Luis Borges', 9990, 20),
('Veinte poemas de amor y una canción desesperada', 'Pablo Neruda', 7990, 25),
('El túnel', 'Ernesto Sábato', 8490, 9),
('Los detectives salvajes', 'Roberto Bolaño', 16990, 6);
