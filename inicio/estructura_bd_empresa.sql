/*
CREATE DATABASE IF NOT EXISTS empresa_inventario;

USE empresa_inventario;

CREATE TABLE roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  descripcion TEXT
);

CREATE TABLE usuarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  telefono VARCHAR(15),
  direccion VARCHAR(255),
  foto_perfil VARCHAR(255),
  fecha_nacimiento DATE,
  rol INT,
  FOREIGN KEY (rol) REFERENCES roles(id)
);

CREATE TABLE categorias (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL
);

CREATE TABLE proveedores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  contacto VARCHAR(100),
  telefono VARCHAR(15),
  direccion VARCHAR(255),
  email VARCHAR(100)
);

CREATE TABLE marcas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL
);

CREATE TABLE equipos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  cantidad INT,
  proveedor_id INT,
  categoria_id INT,
  marca_id INT,
  precio DECIMAL(10, 2),
  caracteristicas TEXT, -- Campo para características
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
  FOREIGN KEY (categoria_id) REFERENCES categorias(id),
  FOREIGN KEY (marca_id) REFERENCES marcas(id)
);

CREATE TABLE fechas_productos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  producto_equipo_id INT NOT NULL,
  fecha_compra DATE,
  fecha_garantia DATE,
  fecha_vida_util DATE,
  codigo_unico_del_equipo VARCHAR(100), -- Campo trasladado aquí
  FOREIGN KEY (producto_equipo_id) REFERENCES equipos(id)
);

CREATE TABLE tipos_mantenimiento (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT
);

CREATE TABLE mantenimientos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  fecha_producto_id INT NOT NULL,
  tipo_mantenimiento_id INT NOT NULL,
  fecha_mantenimiento DATE,
  descripcion TEXT,
  tecnico VARCHAR(100),
  FOREIGN KEY (fecha_producto_id) REFERENCES fechas_productos(id),
  FOREIGN KEY (tipo_mantenimiento_id) REFERENCES tipos_mantenimiento(id)
);

CREATE TABLE login (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  username VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE historial_login (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT,
  fecha_ingreso DATETIME,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

USE empresa_inventario;

select * from usuarios;
select * from login;
SELECT * FROM login WHERE username = 'admin3';
SELECT * FROM usuarios WHERE id = 2;

INSERT INTO roles (nombre, descripcion) VALUES 
('admin', 'Administrador del sistema con acceso completo.'),
('inventarista', 'Responsable de la gestión del inventario.'),
('mantenimiento', 'Encargado de realizar el mantenimiento de equipos.'),
('reportes', 'Generador de informes y reportes del sistema.');




SELECT * FROM usuarios;
-- AGREGAR ROL 
SET SQL_SAFE_UPDATES = 0;-- desactivar el modo seguro
UPDATE usuarios
SET rol = 1  -- Cambia '1' por el ID real si es diferente
WHERE email = 'admin3@gmail.com';
SET SQL_SAFE_UPDATES = 1; -- activar  el modo seguro


*/

USE empresa_inventario;
