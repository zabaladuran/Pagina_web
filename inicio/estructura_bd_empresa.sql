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
('reportes', 'Generador de informes y reportes del sistema.'),
('usuarios', 'selepermite ver mas no modificar nada.');




SELECT * FROM usuarios;
-- AGREGAR ROL 
SET SQL_SAFE_UPDATES = 0;-- desactivar el modo seguro
UPDATE usuarios
SET rol = 1  -- Cambia '1' por el ID real si es diferente
WHERE email = 'admin11@gmail.com';
SET SQL_SAFE_UPDATES = 1; -- activar  el modo seguro


INSERT INTO categorias (nombre) VALUES
('Computadoras'),
('Impresoras'),
('Equipos de red'),
('Televisores'),
('Sistemas de audio'),
('Proyectores'),
('Otros dispositivos electrónicos');


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

select * from marcas;

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

INSERT INTO equipos (nombre, descripcion, cantidad, proveedor_id, categoria_id, marca_id, precio, caracteristicas)
VALUES 
('Laptop Dell XPS 13', 'Laptop de alto rendimiento con pantalla de 13 pulgadas', 10, 1, 1, 5, 1200.00, '16GB RAM, 512GB SSD, Intel Core i7'),
('Impresora HP LaserJet Pro', 'Impresora láser de alta velocidad, ideal para oficinas', 5, 2, 2, 3, 300.00, 'Impresión láser, hasta 40ppm, conectividad inalámbrica'),
('Router TP-Link Archer AX50', 'Router de última generación con Wi-Fi 6', 20, 3, 3, 4, 150.00, 'Wi-Fi 6, Doble banda, hasta 3000Mbps'),
('Televisor Samsung 55" 4K', 'Televisor UHD 4K con HDR', 7, 4, 4, 1, 700.00, 'Resolución 4K, HDR10+, Smart TV'),
('Proyector Epson PowerLite', 'Proyector de alta resolución, ideal para presentaciones en oficina', 3, 5, 6, 6, 450.00, 'Resolución 1080p, 3000 lúmenes, HDMI, Wi-Fi'),
('Smartphone Lenovo Moto G Power', 'Teléfono inteligente de gama media con batería de larga duración', 15, 6, 5, 9, 200.00, '5000mAh, Cámara de 48MP, Pantalla de 6.4"'),
('Sistema de Audio LG XBoom', 'Sistema de audio potente para fiestas', 8, 7, 6, 2, 250.00, 'Bluetooth, 500W, Ecualizador, Luces LED'),
('Switch Cisco SG350-28', 'Switch administrado con 28 puertos Gigabit', 12, 8, 3, 4, 600.00, '28 puertos Gigabit, PoE, VLAN, QoS'),
('Monitor Asus ProArt 27"', 'Monitor profesional de 27 pulgadas para diseño gráfico', 4, 9, 1, 10, 400.00, '27", 4K UHD, 100% sRGB, HDMI, DisplayPort'),
('Tablet Samsung Galaxy Tab S7', 'Tableta de alto rendimiento con pantalla de 11 pulgadas', 9, 10, 5, 1, 650.00, '11" TFT, 128GB almacenamiento, Snapdragon 865+')
;

# codigos para los roles en el registro
# admi                     = 109
# inventarista             = 619
# mantenimiento            = 226
# reporte                  = 610
# usuarios                 = " "
# oña
*/

USE empresa_inventario;




select * from roles;


