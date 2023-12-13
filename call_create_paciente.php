<?php
session_start(); 

$usuario = "root";
$password = "root";
$servidor = "localhost:3306";
$basededatos = "mydb";

$conexion = mysqli_connect($servidor, $usuario, $password) or die("No se ha podido conectar al servidor de Base de datos");

if (!$conexion) {
    die('No se ha podido conectar a la base de datos');
}

$username = mysqli_real_escape_string($conexion, $_POST['username']);
$email = mysqli_real_escape_string($conexion, $_POST['email']);
$passwordUser = mysqli_real_escape_string($conexion, $_POST['passwordUser']);

$db = mysqli_select_db($conexion, $basededatos) or die("No se ha podido conectar a la base de datos");
$consulta = "INSERT INTO pacientes (username, email, password) VALUES ('$username','$email','$passwordUser')";
$resultado = mysqli_query($conexion, $consulta);

if ($resultado) {
    echo "success";
} else {
    echo "error";
}

mysqli_close($conexion);
?>
