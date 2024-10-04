
CREATE DATABASE IF NOT EXISTS empresa_inventario;

USE empresa_inventario;

-- 1. Crear tabla de roles (es independiente)
CREATE TABLE roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL,
  descripcion TEXT
);

-- 2. Crear tabla de estados de equipos (independiente)
CREATE TABLE estados_equipos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(50) NOT NULL
);

-- 3. Crear tabla de categorías de equipos (independiente)
CREATE TABLE categorias (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL
);

-- 4. Crear tabla de proveedores (independiente)
CREATE TABLE proveedores (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  contacto VARCHAR(100),
  telefono VARCHAR(15),
  direccion VARCHAR(255),
  email VARCHAR(100)
);

-- 5. Crear tabla de marcas (independiente)
CREATE TABLE marcas (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL
);

-- 19. Crear tabla de técnicos (dependiente de roles)
CREATE TABLE tecnicos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  apellido VARCHAR(100) NOT NULL,
  telefono VARCHAR(15),
  correo VARCHAR(100),
  rol_id INT,
  FOREIGN KEY (rol_id) REFERENCES roles(id)
);


-- 7. Crear tabla de equipos (sin estado_id)
CREATE TABLE equipos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  cantidad INT,
  proveedor_id INT,
  categoria_id INT,
  marca_id INT,
  precio DECIMAL(10, 2),
  precio_total DECIMAL(10, 2), -- Nuevo campo para precio total
  caracteristicas TEXT,
  FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
  FOREIGN KEY (categoria_id) REFERENCES categorias(id),
  FOREIGN KEY (marca_id) REFERENCES marcas(id)
);

-- 8. Crear tabla de fechas de productos (depende de equipos)
CREATE TABLE fechas_productos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  producto_equipo_id INT NOT NULL,
  fecha_compra DATE,
  fecha_garantia DATE,
  fecha_vida_util DATE,
  codigo_unico_del_equipo VARCHAR(100),
  FOREIGN KEY (producto_equipo_id) REFERENCES equipos(id)
);

-- 9. Crear tabla de tipos de mantenimiento (independiente)
CREATE TABLE tipos_mantenimiento (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  frecuencia_mantenimiento INT NOT NULL DEFAULT 30 -- Frecuencia de mantenimiento en días
);

-- 10. Crear tabla de mantenimientos (con estado_id)
CREATE TABLE mantenimientos (
  id INT PRIMARY KEY AUTO_INCREMENT,
  equipo_id INT NOT NULL,
  tipo_mantenimiento_id INT NOT NULL,
  fecha_mantenimiento DATE,
  descripcion TEXT,
  tecnico_id INT, -- Relación con la tabla de técnicos
  estado_id INT, -- Relación con la tabla de estados
  FOREIGN KEY (equipo_id) REFERENCES equipos(id),
  FOREIGN KEY (tipo_mantenimiento_id) REFERENCES tipos_mantenimiento(id),
  FOREIGN KEY (tecnico_id) REFERENCES tecnicos(id),
  FOREIGN KEY (estado_id) REFERENCES estados_equipos(id)
);

-- 11. Crear tabla de usuarios (depende de roles)
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

-- 12. Crear tabla de login (depende de usuarios)
CREATE TABLE login (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT NOT NULL,
  username VARCHAR(100) NOT NULL,
  password VARCHAR(100) NOT NULL,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 13. Crear tabla de historial de login (depende de usuarios)
CREATE TABLE historial_login (
  id INT PRIMARY KEY AUTO_INCREMENT,
  usuario_id INT,
  fecha_ingreso DATETIME,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- 14. Insertar datos en la tabla roles
INSERT INTO roles (nombre, descripcion) VALUES 
('admin', 'Administrador del sistema con acceso completo.'),
('inventarista', 'Responsable de la gestión del inventario.'),
('mantenimiento', 'Encargado de realizar el mantenimiento de equipos.'),
('reportes', 'Generador de informes y reportes del sistema.'),
('usuarios', 'Permite ver pero no modificar nada.');

-- 15. Insertar datos en la tabla estados_equipos
INSERT INTO estados_equipos (nombre) VALUES
('en mantenimiento'),
('libre'),
('en uso');

-- 16. Insertar datos en la tabla categorías
INSERT INTO categorias (nombre) VALUES
('Computadoras'),
('Impresoras'),
('Equipos de red'),
('Televisores'),
('Sistemas de audio'),
('Proyectores'),
('Otros dispositivos electrónicos');

-- 17. Insertar datos en la tabla marcas
INSERT INTO marcas (nombre) VALUES
('Samsung'),
('LG'),
('HP'),
('Lenovo'),
('Dell'),
('Epson'),
('Canon'),
('Xerox'),
('Asus'),
('Motorola');

-- 18. Insertar datos en la tabla proveedores
INSERT INTO proveedores (nombre, contacto, telefono, direccion, email) VALUES
('Tech Solutions S.A.S.', 'Juan Pérez', '3123456789', 'Carrera 10 #20-30', 'info@techsolutions.com'),
('Electro World Ltda.', 'María Gómez', '3219876543', 'Avenida 5 #10-20', 'contacto@electroworld.com'),
('Innovación Digital S.A.', 'Carlos Ruiz', '3101234567', 'Calle 25 #12-34', 'info@innovaciondigital.com'),
('Sistemas Avanzados S.A.S.', 'Ana Torres', '3174567890', 'Carrera 50 #30-40', 'info@sistemasavanzados.com'),
('Red de Soluciones Electrónicas', 'Luis Martínez', '3136547890', 'Avenida 15 #22-25', 'info@redsoluciones.com'),
('Dispositivos y Tecnología S.A.S.', 'Julián Morales', '3145678901', 'Calle 10 #15-20', 'info@dispositivosyt.com'),
('Soluciones Electrónicas de Colombia', 'Sofía León', '3112345678', 'Calle 30 #14-18', 'contacto@solucioneselectronicas.com'),
('Grupo Electrónico S.A.S.', 'Diego López', '3159876543', 'Carrera 12 #5-10', 'info@grupoelectronico.com'),
('Almacenamiento y Redes S.A.', 'Claudia Romero', '3128765432', 'Avenida 40 #30-45', 'info@almacenamiento.com'),
('Electrocomp S.A.S.', 'Esteban Rojas', '3107890123', 'Calle 22 #10-15', 'info@electrocomp.com');

-- 19. Insertar datos en la tabla tipos de mantenimiento
INSERT INTO tipos_mantenimiento (nombre) VALUES
('Preventivo'),
('Correctivo'),
('Predictivo'),
('De Emergencia');


-- Insertar un técnico de ejemplo
INSERT INTO tecnicos (nombre, apellido, telefono, correo, rol_id) VALUES
('Carlos', 'Pérez', '3001234567', 'carlos.perez@example.com', (SELECT id FROM roles WHERE nombre = 'mantenimiento'));
