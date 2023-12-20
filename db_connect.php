<?php
$DATABASE_HOST = 'localhost:3306';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'mydb';

// Conexión a la base de datos
$conexion = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($conexion->connect_error) {
    exit('Fallo en la conexión de MySQL: ' . $conexion->connect_error);
}
?>