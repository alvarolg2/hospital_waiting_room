<?php
$DATABASE_HOST = 'localhost:3306';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'mydb';

// Conexión a la base de datos
$connection = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($connection->connect_error) {
    exit('Fallo en la conexión de MySQL: ' . $connection->connect_error);
}
?>