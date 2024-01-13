<?php
session_start(); 

$usuario = "root";
$password = "root";
$servidor = "localhost:3306";
$basededatos = "mydb";

$connection = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

if (!$connection) {
    die('No se ha podido conectar a la base de datos');
}

$username = mysqli_real_escape_string($connection, $_POST['username']);
$email = mysqli_real_escape_string($connection, $_POST['email']);
$passwordUser = mysqli_real_escape_string($connection, $_POST['passwordUser']);

$db = mysqli_select_db($connection, $basededatos) or die("No se ha podido conectar a la base de datos");
$query = "INSERT INTO patients (username, email, password) VALUES ('$username','$email','$passwordUser')";
$result = mysqli_query($connection, $query);

if ($result) {
    echo "success";
} else {
    echo "error";
}

mysqli_close($connection);
?>
