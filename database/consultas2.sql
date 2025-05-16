USE hotel;

-- Insertar tipo de habitación
INSERT INTO tipohabitaciones (tipohabitacion) VALUES ('Doble');

-- Insertar habitación
INSERT INTO habitaciones (idtipohabitacion, numero, piso, numcamas, precioregular, estado)
VALUES (1, '101', 1, 2, 150.00, 'disponible');

-- Insertar rol y colaborador
INSERT INTO roles (rol) VALUES ('Recepcionista');

-- Persona colaborador
INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono, fechanac)
VALUES ('DNI', '11111111', 'López', 'Carlos', '987654321', '1990-01-01');

-- Contrato del colaborador
INSERT INTO contratos (idpersona, idrol, fechainicio)
VALUES (1, 1, '2024-01-01');

-- Colaborador
INSERT INTO colaboradores (idcontrato) VALUES (1);

-- Medio de pago
INSERT INTO mediopago (mediopago) VALUES ('Efectivo');


-- Persona cliente
INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono, fechanac)
VALUES ('DNI', '22222222', 'Gonzales', 'Laura', '987111111', '1992-03-05');

-- Cliente
INSERT INTO clientes (idpersona) VALUES (2);  -- idcliente = 1

-- Persona acompañante 1
INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono, fechanac)
VALUES ('DNI', '33333333', 'Gonzales', 'Ana', '987222222', '2010-06-15');

-- Persona acompañante 2
INSERT INTO personas (tipodoc, numerodoc, apellidos, nombres, telefono, fechanac)
VALUES ('DNI', '44444444', 'Ramirez', 'Jorge', '987333333', '1988-08-20');

INSERT INTO alquileres (
    idcliente, idhabitacion, idcolaboradorentrada, fechahorainicio, fechahorafin,
    valoralquiler, modalidadpago, idmediopago
) VALUES (
    1, 1, 1, NOW(), DATE_ADD(NOW(), INTERVAL 2 DAY), 300.00, 'Contado', 1
);
-- idalquiler = 1

-- Acompañante 1 (hija)
INSERT INTO huespedes (
    idcliente, idalquiler, idpersona, tipohuesped, parentesco, observaciones
) VALUES (
    1, 1, 3, 'acompañante', 'hija', 'Menor de edad'
);

-- Acompañante 2 (pareja)
INSERT INTO huespedes (
    idcliente, idalquiler, idpersona, tipohuesped, parentesco, observaciones
) VALUES (
    1, 1, 4, 'acompañante', 'esposo', 'Tiene alergia al polvo'
);

SELECT 
    h.numero AS habitacion,
    p.nombres,
    p.apellidos,
    hu.tipohuesped,
    hu.parentesco
FROM habitaciones h
JOIN alquileres a ON h.idhabitacion = a.idhabitacion
JOIN huespedes hu ON a.idalquiler = hu.idalquiler
JOIN personas p ON hu.idpersona = p.idpersona
WHERE h.numero = '101';
SELECT 
    a.idalquiler,
    h.numero AS habitacion,
    p.nombres AS cliente_nombres,
    p.apellidos AS cliente_apellidos,
    a.fechahorainicio,
    a.fechahorafin,
    a.valoralquiler,
    a.modalidadpago,
    mp.mediopago
FROM alquileres a
JOIN clientes c ON a.idcliente = c.idcliente
JOIN personas p ON c.idpersona = p.idpersona
JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
LEFT JOIN mediopago mp ON a.idmediopago = mp.idmediopago
ORDER BY a.fechahorainicio DESC;

SELECT 
    a.idalquiler,
    h.numero AS habitacion,
    cp.nombres AS cliente_nombres,
    cp.apellidos AS cliente_apellidos,
    hp.nombres AS huesped_nombres,
    hp.apellidos AS huesped_apellidos,
    hu.tipohuesped,
    hu.parentesco
FROM alquileres a
JOIN clientes c ON a.idcliente = c.idcliente
JOIN personas cp ON c.idpersona = cp.idpersona  -- cliente
JOIN habitaciones h ON a.idhabitacion = h.idhabitacion
JOIN huespedes hu ON a.idalquiler = hu.idalquiler
JOIN personas hp ON hu.idpersona = hp.idpersona  -- huesped
ORDER BY a.idalquiler, hu.idhuesped;
