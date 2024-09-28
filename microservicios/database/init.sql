CREATE DATABASE IF NOT EXISTS usuariosDB;
USE usuariosDB;

CREATE TABLE IF NOT EXISTS usuarios (
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(30) NOT NULL,
    usuario VARCHAR(20) NOT NULL PRIMARY KEY,
    password VARCHAR(30) NOT NULL
);

-- Insertar usuarios
INSERT INTO usuarios (nombre, email, usuario, password) VALUES
('Admin', 'admin@example.com', 'admin', 'admin'),
('User', 'user@example.com', 'user123', 'userpassword');


-- Crear base de datos para productos
CREATE DATABASE IF NOT EXISTS productosDB;
USE productosDB;

CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)NOT NULL,
    precio DECIMAL(10, 2)NOT NULL,
    inventario INT NOT NULL
);

-- Crear base de datos para órdenes
CREATE DATABASE IF NOT EXISTS ordenesDB;
USE ordenesDB;

CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreCliente VARCHAR(50)NOT NULL,
    emailCliente VARCHAR(50)NOT NULL,
    totalCuenta DECIMAL(10, 2) NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP
);

