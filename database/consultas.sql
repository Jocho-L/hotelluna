
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