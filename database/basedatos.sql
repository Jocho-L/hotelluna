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
    estado VARCHAR(50) NOT NULL,
    FOREIGN KEY (idtipohabitacion) REFERENCES tipohabitaciones(idtipohabitacion) ON DELETE CASCADE
);

-- Tabla de roles
CREATE TABLE roles (
    idrol INT AUTO_INCREMENT PRIMARY KEY, 
    rol VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de empresas
CREATE TABLE empresas (
    idempresa INT AUTO_INCREMENT PRIMARY KEY,
    ruc VARCHAR(20) NOT NULL UNIQUE,
    razonsocial VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    telefono VARCHAR(20) UNIQUE
);

-- Tabla de personas
CREATE TABLE personas (
    idpersona INT AUTO_INCREMENT PRIMARY KEY,
    tipodoc VARCHAR(10) NOT NULL,
    numerodoc VARCHAR(9) NOT NULL UNIQUE,
    apellidos VARCHAR(100) NOT NULL,
    nombres VARCHAR(100) NOT NULL,
    telefono CHAR(9) UNIQUE,
    fechanac DATE NOT NULL
);

-- Tabla de clientes
CREATE TABLE clientes (
    idcliente INT AUTO_INCREMENT PRIMARY KEY,
    idempresa INT,
    idpersona INT NOT NULL,
    FOREIGN KEY (idempresa) REFERENCES empresas(idempresa) ON DELETE SET NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE
);

-- Tabla de contratos
CREATE TABLE contratos (
    idcontrato INT AUTO_INCREMENT PRIMARY KEY,
    idpersona INT NOT NULL,
    idrol INT NOT NULL,
    fechainicio DATE NOT NULL,
    fechafin DATE,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE,
    FOREIGN KEY (idrol) REFERENCES roles(idrol) ON DELETE CASCADE
);

-- Tabla de colaboradores
CREATE TABLE colaboradores (
    idcolaborador INT AUTO_INCREMENT PRIMARY KEY,
    idcontrato INT NOT NULL,
    FOREIGN KEY (idcontrato) REFERENCES contratos(idcontrato) ON DELETE CASCADE
);

-- Tabla de usuarios (Nueva)
CREATE TABLE usuarios (
    idusuario INT AUTO_INCREMENT PRIMARY KEY,
    idcolaborador INT NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    FOREIGN KEY (idcolaborador) REFERENCES colaboradores(idcolaborador) ON DELETE CASCADE
);

-- Tabla de medios de pago
CREATE TABLE mediopago (
    idmediopago INT AUTO_INCREMENT PRIMARY KEY,
    mediopago VARCHAR(100) NOT NULL UNIQUE
);

-- Tabla de alquileres
CREATE TABLE alquileres (
    idalquiler INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    idhabitacion INT NOT NULL,
    idcolaboradorentrada INT NOT NULL,
    idcolaboradorsalida INT DEFAULT NULL,
    idmediopago INT DEFAULT NULL,
    fechahorainicio DATETIME NOT NULL,
    fechahorafin DATETIME NOT NULL,
    valoralquiler DECIMAL(10,2) NOT NULL,
    modalidadpago VARCHAR(50) NOT NULL,
    numtransaccion VARCHAR(50) DEFAULT NULL,
    lugarprocedencia VARCHAR(100) NULL,
    observaciones VARCHAR(200) NULL,
    FOREIGN KEY (idcliente) REFERENCES clientes(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idcolaboradorentrada) REFERENCES colaboradores(idcolaborador) ON DELETE CASCADE,
    FOREIGN KEY (idcolaboradorsalida) REFERENCES colaboradores(idcolaborador) ON DELETE SET NULL,
    FOREIGN KEY (idmediopago) REFERENCES mediopago(idmediopago) ON DELETE SET NULL
);
CREATE TABLE huespedes (
    idhuesped INT AUTO_INCREMENT PRIMARY KEY,
    idcliente INT NOT NULL,
    idalquiler INT DEFAULT NULL,
    idpersona INT NOT NULL,
    observaciones TEXT,
    tipohuesped VARCHAR(50) NOT NULL,
    parentesco VARCHAR(50),
    FOREIGN KEY (idcliente) REFERENCES clientes(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (idalquiler) REFERENCES alquileres(idalquiler) ON DELETE SET NULL,
    FOREIGN KEY (idpersona) REFERENCES personas(idpersona) ON DELETE CASCADE
) ENGINE=InnoDB;



-- Tabla de créditos
CREATE TABLE creditos (
    idcredito INT AUTO_INCREMENT PRIMARY KEY,
    idalquiler INT NOT NULL,
    fechalimite DATE NOT NULL,
    interes DECIMAL(5,2) NOT NULL,
    FOREIGN KEY (idalquiler) REFERENCES alquileres(idalquiler) ON DELETE CASCADE
);

-- Tabla de amortizaciones
CREATE TABLE amortizaciones (
    idamortizacion INT AUTO_INCREMENT PRIMARY KEY,
    idcredito INT NOT NULL,
    idmediopago INT NULL,
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
    idcolaboradorregistro INT NOT NULL,
    FOREIGN KEY (idcliente) REFERENCES clientes(idcliente) ON DELETE CASCADE,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idcolaboradorregistro) REFERENCES colaboradores(idcolaborador) ON DELETE CASCADE
);

-- Tabla de mantenimientos
CREATE TABLE mantenimientos (
    idmantenimiento INT AUTO_INCREMENT PRIMARY KEY,
    idhabitacion INT NOT NULL,
    idcontrato INT NOT NULL,
    fechahorainicio DATETIME NOT NULL,
    fechahorafin DATETIME NOT NULL,
    observaciones TEXT,
    FOREIGN KEY (idhabitacion) REFERENCES habitaciones(idhabitacion) ON DELETE CASCADE,
    FOREIGN KEY (idcontrato) REFERENCES contratos(idcontrato) ON DELETE CASCADE
);
