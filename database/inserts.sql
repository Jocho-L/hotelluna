USE hotel;

-- Insertar tipos de habitaciones
INSERT INTO tipohabitaciones (tipohabitacion) VALUES 
('Individual'),
('Doble'),
('Suite'),
('Matrimonial');

-- Insertar habitaciones
INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado) VALUES 
(1, '101', 1, 1, 50.00, 'Disponible'),
(1, '102', 1, 1, 50.00, 'Ocupado'),
(2, '201', 2, 2, 80.00, 'Disponible'),
(2, '202', 2, 2, 80.00, 'Mantenimiento'),
(3, '301', 3, 1, 150.00, 'Disponible'),
(3, '302', 3, 1, 150.00, 'Reservado'),
(4, '401', 4, 1, 120.00, 'Disponible'),
(4, '402', 4, 1, 120.00, 'Ocupado');

INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono) 
VALUES 
('DNI', '12345678', 'Pérez Gómez', 'Juan', '987654321'),
('DNI', '87654321', 'González Ramos', 'María', '912345678'),
('CE', 'A1234567', 'Smith Johnson', 'Michael', '954321987'),
('DNI', '56781234', 'Fernández López', 'Ana', '934567890'), 
('PASAPORTE', 'AB123456', 'Chang Wei', 'Li', '945678123');

INSERT INTO clientes (idpersona) VALUES
(1),
(2),
(3),
(4),
(5);