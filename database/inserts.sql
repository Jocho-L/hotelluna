
USE hotel;

-- Insertar tipos de habitaciones
INSERT INTO tipohabitaciones (tipohabitacion) VALUES
('Simple'),
('Doble'),
('Triple'),
('Familiar'),
('Matrimonial Económico'),
('Matrimonial Estandar'),
('Matrimonial VIP');

INSERT INTO roles (rol) VALUES
('Administrador'),
('Recepcionista');


-- Insertar habitaciones (31 registros)
-- Insertar habitaciones (31 registros)
INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado) VALUES
-- Piso 1
  (1, '101', 1, 1, 35.00, 'Disponible'),
  (2, '102', 1, 2, 50.00, 'Disponible'),
  (3, '103', 1, 3, 65.00, 'Disponible'),
  (4, '104', 1, 4, 80.00, 'Disponible'),
  (5, '105', 1, 1, 40.00, 'Disponible'),
  (6, '106', 1, 1, 50.00, 'Disponible'),
  (7, '107', 1, 1, 70.00, 'Disponible'),
  (1, '108', 1, 1, 35.00, 'Disponible'),
  (2, '109', 1, 2, 50.00, 'Disponible'),
  (3, '110', 1, 3, 65.00, 'Disponible'),
  (4, '111', 1, 4, 80.00, 'Disponible'),
  (5, '201', 2, 1, 40.00, 'Disponible'),
  (6, '202', 2, 1, 50.00, 'Disponible'),
  (7, '203', 2, 1, 70.00, 'Disponible'),
  (1, '204', 2, 1, 35.00, 'Disponible'),
  (2, '205', 2, 2, 50.00, 'Disponible'),
  (3, '206', 2, 3, 65.00, 'Disponible'),
  (4, '207', 2, 4, 80.00, 'Disponible'),
  (5, '208', 2, 1, 40.00, 'Disponible'),
  (6, '209', 2, 1, 50.00, 'Disponible'),
  (7, '301', 3, 1, 70.00, 'Disponible'),
  (1, '302', 3, 1, 35.00, 'Disponible'),
  (2, '303', 3, 2, 50.00, 'Disponible'),
  (3, '304', 3, 3, 65.00, 'Disponible'),
  (4, '305', 3, 4, 80.00, 'Disponible'),
  (5, '306', 3, 1, 40.00, 'Disponible'),
  (6, '307', 3, 1, 50.00, 'Disponible'),
  (7, '308', 3, 1, 70.00, 'Disponible'),
  (1, '309', 3, 1, 35.00, 'Disponible'),
  (2, '310', 3, 2, 50.00, 'Disponible'),
  (3, '311', 3, 3, 65.00, 'Disponible');

-- 1. Insertar persona
INSERT INTO personas (tipodoc, numerodoc, apellidos, genero, nombres, telefono, fechanac)
VALUES ('DNI', '72015783', 'HERNANDEZ SARAVIA', 'masculino', 'JOSE MANUEL', '941895694', '2003-10-24');

-- 2. Insertar rol (si aún no existe)
INSERT INTO roles (rol) VALUES ('Administrador');
INSERT INTO roles (rol) VALUES ('Recepcionista');
-- 3. Insertar usuario
INSERT INTO usuarios (idpersona, idrol, username, password, estado)
VALUES (1, 1, 'admin', '$2y$10$HQZ//tv2lxLnUVAnP7Djv.lTY7Tip02bhfjzOEeZeT/y4y.SIA5wC', 'activo');
-- admin123

SELECT * FROM usuarios;
