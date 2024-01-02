<?php
session_start();
include 'db_connect.php';
// Credenciales de acceso a la base de datos

$role = $_POST['role'];
$table = ($role == 'pacientes') ? 'pacientes' : 'personal';
$username = $_POST['username'];
$password = $_POST['password'];
$superUser = isset($_POST['superuser']) ? 1 : 0;


// Preparar consulta SQL
$query = "SELECT * FROM $table WHERE username = '$username' and password = '$password'";
if ($role == 'personal') {
    $query .= " and super_user = $superUser";
}
$validate_user = mysqli_query($conexion,$query);
if(!$validate_user){
   
    exit('Usuario incorrecto!');
}
if($usuario = mysqli_fetch_assoc($validate_user) or $password = mysqli_fetch_assoc($validate_user)){
    $_SESSION['user'] = $username;
    header("location:home_personal.php");
    exit;
}
else {
    exit('Usuario o contraseña incorrectos');
}

$conexion->close();
?>
