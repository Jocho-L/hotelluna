
DROP DATABASE IF EXISTS hotel;

CREATE DATABASE hotel;
USE hotel;

-- Tabla de tipos de habitaciones
CREATE TABLE tipohabitaciones (
    idtipohabitacion INT AUTO_INCREMENT PRIMARY KEY,
    tipohabitacion VARCHAR(100) NOT NULL
);

-- Tabla de habitaciones
CREATE TABLE habitaciones (
    idhabitacion INT AUTO_INCREMENT PRIMARY KEY,
    idtipohabitacion INT NOT NULL,
    numero VARCHAR(10) NOT NULL UNIQUE,
    piso INT NOT NULL,
    numcamas INT NOT NULL,
    precioregular DECIMAL(10,2) NOT NULL,
    estado ENUM('disponible', 'ocupada', 'mantenimiento', 'reservado', 'deshabilitado') NOT NULL DEFAULT 'disponible',
    FOREIGN KEY (idtipohabitacion) REFERENCES tipohabitaciones(idtipohabitacion) ON DELETE CASCADE
);

-- Tabla de roles
CREATE TABLE roles (
    idrol INT AUTO_INCREMENT PRIMARY KEY,
    rol VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de empresas (opcional para clientes corporativos)
CREATE TABLE empresas (
    idempresa INT AUTO_INCREMENT PRIMARY KEY,
    ruc VARCHAR(20) NOT NULL UNIQUE,
    razonsocial VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20)
);

-- Tabla de personas
CREATE TABLE personas (
    idpersona INT AUTO_INCREMENT PRIMARY KEY,
    tipodoc VARCHAR(10) NOT NULL,
    numerodoc VARCHAR(9) NOT NULL UNIQUE,
    apellidos VARCHAR(100) NOT NULL,
    genero ENUM('masculino', 'femenino') NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    telefono CHAR(9),
    fechanac DATE NOT NULL
);


-- Tabla de clientes
CREATE TABLE clientes (
    idcliente INT AUTO_INCREMENT PRIMARY KEY,
    idempresa INT NULL,
    idpersona INT NOT NULL,
    FOREIGN KEY (idempresa) REFERENCES empresas(idempresa) ON DELETE SET NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE
);

-- Tabla de usuarios (recepcionistas, administradores, etc.)
CREATE TABLE usuarios (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    idpersona INT NOT NULL,
    idrol INT NOT NULL,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE,
    FOREIGN KEY (idrol) REFERENCES roles(idrol) ON DELETE CASCADE
);

-- Tabla de medios de pago
CREATE TABLE mediopago (
    idmediopago INT AUTO_INCREMENT PRIMARY KEY,
    mediopago VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de alquileres (registra check-in y check-out)
CREATE TABLE alquileres (
    idalquiler INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    idhabitacion INT NOT NULL,
    idusuarioentrada INT NOT NULL,
    idusuariosalida INT DEFAULT NULL,
    idmediopago INT DEFAULT NULL,
    fechahorainicio DATETIME NOT NULL,
    fechahorafin DATETIME DEFAULT NULL,
    valoralquiler DECIMAL(10,2) DEFAULT NULL,
    modalidadpago VARCHAR(50) DEFAULT NULL,
    numtransaccion VARCHAR(50) DEFAULT NULL,
    lugarprocedencia VARCHAR(100) DEFAULT NULL,
    observaciones VARCHAR(255),
    posobservaciones VARCHAR(255) DEFAULT NULL,
    placa VARCHAR(30) DEFAULT NULL,
    incluyedesayuno BOOLEAN NOT NULL DEFAULT FALSE,
    FOREIGN KEY (idcliente) REFERENCES clientes(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idusuarioentrada) REFERENCES usuarios(idusuario) ON DELETE CASCADE,
    FOREIGN KEY (idusuariosalida) REFERENCES usuarios(idusuario) ON DELETE SET NULL,
    FOREIGN KEY (idmediopago) REFERENCES mediopago(idmediopago) ON DELETE SET NULL
);

-- Tabla de huéspedes (acompañantes del cliente principal)
CREATE TABLE huespedes (
    idhuesped INT AUTO_INCREMENT PRIMARY KEY,
    idalquiler INT NOT NULL,
    idpersona INT NOT NULL,
    observaciones VARCHAR(50),
    tipohuesped VARCHAR(50) NOT NULL,
    parentesco VARCHAR(50),
    idresponsable INT NULL,
    cartapoder TEXT DEFAULT NULL,
    FOREIGN KEY (idresponsable) REFERENCES personas(idpersona) ON DELETE SET NULL,
    FOREIGN KEY (idalquiler) REFERENCES alquileres(idalquiler) ON DELETE CASCADE,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE
);


-- Tabla de créditos (alquileres fiados)
CREATE TABLE creditos (
    idcredito INT AUTO_INCREMENT PRIMARY KEY,
    idalquiler INT NOT NULL,
    fechalimite DATE NOT NULL,
    interes DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (idalquiler) REFERENCES alquileres(idalquiler) ON DELETE CASCADE
);

-- Tabla de amortizaciones (pagos parciales de créditos)
CREATE TABLE amortizaciones (
    idamortizacion INT AUTO_INCREMENT PRIMARY KEY,
    idcredito INT NOT NULL,
    idmediopago INT DEFAULT NULL,
    fecha DATE NOT NULL,
    amortizacion DECIMAL(10,2) NOT NULL,
    saldo DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (idcredito) REFERENCES creditos(idcredito) ON DELETE CASCADE,
    FOREIGN KEY (idmediopago) REFERENCES mediopago(idmediopago) ON DELETE SET NULL
);

-- Tabla de cotizaciones
CREATE TABLE cotizaciones (
    idcotizacion INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    idhabitacion INT NOT NULL,
    fechainicio DATE NOT NULL,
    fechafin DATE NOT NULL,
    idusuarioregistro INT NOT NULL,
    FOREIGN KEY (idcliente) REFERENCES clientes(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idusuarioregistro) REFERENCES usuarios(idusuario) ON DELETE CASCADE
);

-- Tabla de mantenimientos
CREATE TABLE mantenimientos (
    idmantenimiento INT AUTO_INCREMENT PRIMARY KEY,
    idhabitacion INT NOT NULL,
    idusuario INT NOT NULL,
    fechahorainicio DATETIME NOT NULL,
    fechahorafin DATETIME NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario) ON DELETE CASCADE
);
CREATE TABLE egresos (
    idegreso INT AUTO_INCREMENT PRIMARY KEY,
    idusuario INT NOT NULL,
    fecha DATE NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (idusuario) REFERENCES usuarios(idusuario) ON DELETE CASCADE
);