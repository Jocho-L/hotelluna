use hotel;

select * from habitaciones;

select * from clientes;

select * from personas;

SELECT * FROM alquileres

SELECT * FROM roles

SELECT * FROM colaboradores

SELECT * FROM usuarios

SELECT * FROM clientes WHERE idcliente = :idcliente;

SELECT idcliente, dni, nombre, apellido 
FROM clientes 
WHERE dni LIKE '%tu_termino%' OR nombre LIKE '%tu_termino%' OR apellido LIKE '%tu_termino%';