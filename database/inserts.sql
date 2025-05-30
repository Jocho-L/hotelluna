
USE hotel;

-- Insertar tipos de habitaciones
INSERT INTO tipohabitaciones (tipohabitacion) VALUES
('Individual'),
('Doble'),
('Suite'),
('Matrimonial');


INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado) VALUES
(1, '101', 1, 1, 50.00, 'Disponible'),
(1, '102', 1, 1, 50.00, 'Ocupada'),
(1, '103', 1, 1, 50.00, 'Disponible'),
(1, '104', 1, 1, 50.00, 'Mantenimiento'),
(1, '105', 1, 1, 50.00, 'Reservado'),
(1, '106', 1, 1, 50.00, 'Disponible'),
(1, '107', 1, 1, 50.00, 'Ocupada'),
(1, '108', 1, 1, 50.00, 'Disponible'),
(1, '109', 1, 1, 50.00, 'Disponible'),
(1, '110', 1, 1, 50.00, 'Mantenimiento'),

(2, '201', 2, 2, 80.00, 'Disponible'),
(2, '202', 2, 2, 80.00, 'Mantenimiento'),
(2, '203', 2, 2, 80.00, 'Reservado'),
(2, '204', 2, 2, 80.00, 'Disponible'),
(2, '205', 2, 2, 80.00, 'Ocupada'),
(2, '206', 2, 2, 80.00, 'Disponible'),
(2, '207', 2, 2, 80.00, 'Disponible'),
(2, '208', 2, 2, 80.00, 'Mantenimiento'),
(2, '209', 2, 2, 80.00, 'Ocupada'),
(2, '210', 2, 2, 80.00, 'Disponible'),

(3, '301', 3, 1, 150.00, 'Disponible'),
(3, '302', 3, 1, 150.00, 'Reservado'),
(3, '303', 3, 1, 150.00, 'Disponible'),
(3, '304', 3, 1, 150.00, 'Ocupada'),
(3, '305', 3, 1, 150.00, 'Disponible'),
(3, '306', 3, 1, 150.00, 'Mantenimiento'),
(3, '307', 3, 1, 150.00, 'Disponible'),
(3, '308', 3, 1, 150.00, 'Reservado'),
(3, '309', 3, 1, 150.00, 'Ocupada'),
(3, '310', 3, 1, 150.00, 'Disponible'),

(4, '401', 4, 1, 120.00, 'Disponible'),
(4, '402', 4, 1, 120.00, 'Ocupada'),
(4, '403', 4, 1, 120.00, 'Mantenimiento'),
(4, '404', 4, 1, 120.00, 'Reservado'),
(4, '405', 4, 1, 120.00, 'Disponible'),
(4, '406', 4, 1, 120.00, 'Ocupada'),
(4, '407', 4, 1, 120.00, 'Disponible'),
(4, '408', 4, 1, 120.00, 'Disponible'),
(4, '409', 4, 1, 120.00, 'Reservado'),
(4, '410', 4, 1, 120.00, 'Disponible'),

(1, '501', 5, 1, 50.00, 'Disponible'),
(2, '502', 5, 2, 80.00, 'Reservado'),
(3, '503', 5, 1, 150.00, 'Ocupada'),
(4, '504', 5, 1, 120.00, 'Mantenimiento'),
(1, '505', 5, 1, 50.00, 'Disponible'),
(2, '506', 5, 2, 80.00, 'Ocupada'),
(3, '507', 5, 1, 150.00, 'Disponible'),
(4, '508', 5, 1, 120.00, 'Reservado'),
(1, '509', 5, 1, 50.00, 'Mantenimiento'),
(2, '510', 5, 2, 80.00, 'Disponible');

-- 1. Insertar persona
INSERT INTO personas (tipodoc, numerodoc, apellidos, genero, nombres, telefono, fechanac)
VALUES ('DNI', '72015783', 'HERNANDEZ SARAVIA', 'masculino', 'JOSE MANUEL', '999999999', '2003-10-24');

-- 2. Insertar rol (si aún no existe)
INSERT INTO roles (rol) VALUES ('Administrador');
-- 3. Insertar usuario
INSERT INTO usuarios (idpersona, idrol, username, password, estado)
VALUES (1, 1, 'jm', 'admin', 'activo');


SELECT * FROM usuarios;

-- Insertar habitaciones
INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado) VALUES
(1, '101', 1, 1, 50.00, 'Disponible'),
(1, '102', 1, 1, 50.00, 'Ocupada'),
(2, '201', 2, 2, 80.00, 'Disponible'),
(2, '202', 2, 2, 80.00, 'Mantenimiento'),
(3, '301', 3, 1, 150.00, 'Disponible'),
(3, '302', 3, 1, 150.00, 'Reservado'),
(4, '401', 4, 1, 120.00, 'Disponible'),
(4, '402', 4, 1, 120.00, 'Ocupada');

INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono)
VALUES
('DNI', '12345678', 'Pérez Gómez', 'Juan', '987654321'),
('DNI', '87654321', 'González Ramos', 'María', '912345678'),
('CE', 'A1234567', 'Smith Johnson', 'Michael', '954321987'),
('DNI', '56781234', 'Fernández López', 'Ana', '934567890'),
('PASAPORTE', 'AB123456', 'Chang Wei', 'Li', '945678123'),
('DNI', '23456789', 'Martínez Castro', 'Luis', '923456789'),
('DNI', '34567890', 'Rojas Quispe', 'Elena', '976543210'),
('CE', 'B2345678', 'Johnson Lee', 'Emily', '958761234'),
('DNI', '45678901', 'Torres Vargas', 'Carlos', '967812345'),
('PASAPORTE', 'CD234567', 'Kumar Patel', 'Ravi', '956781234'),
('DNI', '56789012', 'Ramírez Díaz', 'Gabriela', '934567123'),
('DNI', '67890123', 'Cruz Medina', 'Andrés', '943216789'),
('CE', 'C3456789', 'Williams Brown', 'Sarah', '932187654'),
('DNI', '78901234', 'Ortega Peña', 'Diego', '921345678'),
('PASAPORTE', 'EF345678', 'Nguyen Tran', 'Hoa', '955432189'),
('DNI', '89012345', 'Sánchez Rivas', 'Laura', '901234567'),
('DNI', '90123456', 'Vega Torres', 'Ricardo', '977654321'),
('CE', 'D4567890', 'Taylor Martin', 'Jessica', '966712345'),
('DNI', '01234567', 'Morales Aguirre', 'Pedro', '922334455'),
('PASAPORTE', 'GH456789', 'Ali Fahad', 'Omar', '933445566'),
('DNI', '13243546', 'Flores Campos', 'Lucía', '978901234'),
('DNI', '21354657', 'López Chávez', 'Esteban', '944556677'),
('CE', 'E5678901', 'Robinson Moore', 'Linda', '955667788'),
('DNI', '32465768', 'Navarro Paredes', 'Carmen', '922778899'),
('PASAPORTE', 'IJ567890', 'Yamamoto Sato', 'Kenji', '911223344')

INSERT INTO clientes (idpersona) VALUES