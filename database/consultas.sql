use hotel;

select * from egresos;
select * from habitaciones;

select * from clientes;

select * from personas;
DELETE FROM personas WHERE idpersona = 12;

SELECT * FROM alquileres

SELECT * FROM mediopago

SELECT * FROM roles

SELECT * FROM huespedes

SELECT * FROM colaboradores

SELECT * FROM usuarios

SELECT * FROM clientes WHERE idcliente = :idcliente;

SELECT idcliente, dni, nombre, apellido
FROM clientes
WHERE dni LIKE '%tu_termino%' OR nombre LIKE '%tu_termino%' OR apellido LIKE '%tu_termino%';

SELECT 
    h.idhuesped,
    p.nombres AS nombre_huesped,
    p.apellidos AS apellido_huesped,
    p.fechanac,
    h.tipohuesped,
    h.parentesco,
    CONCAT(rp.nombres, ' ', rp.apellidos) AS nombre_responsable
FROM huespedes h
INNER JOIN personas p ON h.idpersona = p.idpersona
LEFT JOIN personas rp ON h.idresponsable = rp.idpersona
WHERE h.idalquiler = :idalquiler;

