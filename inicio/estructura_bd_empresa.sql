-- Crear la base de datos
CREATE DATABASE empresa_inventario;

-- Usar la base de datos creada
USE empresa_inventario;

-- Tabla de roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255)
);

-- Tabla de proveedores
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100), -- Información de contacto del proveedor
    telefono VARCHAR(20),
    direccion TEXT,
    email VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    foto_perfil VARCHAR(255), -- Ruta de la foto de perfil
    fecha_nacimiento DATE, -- Fecha de nacimiento del usuario
    email_verificado BOOLEAN DEFAULT FALSE, -- Verificación de correo
    terminos_aceptados BOOLEAN DEFAULT FALSE, -- Aceptación de términos
    fecha_aceptacion_terminos TIMESTAMP, -- Fecha de aceptación de términos
    token_reset_password VARCHAR(255), -- Token para restablecer contraseña
    token_expiracion TIMESTAMP NULL, -- Permitir NULL en la expiración del token
    role_id INT NOT NULL, -- Rol del usuario
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);


-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    cantidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    fecha_ingreso DATE NOT NULL,
    proveedor_id INT, -- Relación con proveedores
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
);

-- Tabla de mantenimientos (sin el campo 'costo')
CREATE TABLE mantenimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    fecha_mantenimiento DATE NOT NULL,
    descripcion TEXT,
    tecnico VARCHAR(100), -- Nombre del técnico que realiza el mantenimiento
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- Tabla de logs (registro de actividades de los usuarios)
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    accion VARCHAR(255) NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabla de historial de cambios (registro de cambios en datos personales)
CREATE TABLE historial_cambios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    campo_modificado VARCHAR(100),
    valor_anterior TEXT,
    valor_nuevo TEXT,
    fecha_modificacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

