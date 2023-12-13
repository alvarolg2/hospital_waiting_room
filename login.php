<?php
session_start();

// Credenciales de acceso a la base de datos
$DATABASE_HOST = 'localhost:3306';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'root';
$DATABASE_NAME = 'mydb';

// Conexión a la base de datos
$conexion = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($conexion->connect_error) {
    exit('Fallo en la conexión de MySQL: ' . $conexion->connect_error);
}

// Validación de los datos enviados
if (!isset($_POST['username'], $_POST['password'])) {
    exit('Por favor complete ambos campos!');
}

$role = $_POST['role'];
$table = ($role == 'pacientes') ? 'pacientes_id' : 'personal_id';

// Preparar consulta SQL
$query = "SELECT $table, password FROM pacientes WHERE username = ?";
$stmt = $conexion->prepare($query);
if (!$stmt) {
    exit('Error en la preparación de la consulta: ' . $conexion->error);
}

// Vinculación de parámetros
$stmt->bind_param('s', $_POST['username']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($_POST['password'], $row['password'])) {
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $_POST['username'];
        $_SESSION['id'] = $row[$table];
        header('Location: inicio_pacientes.php');
        exit;
    } else {
        exit('Contraseña incorrecta!');
    }
} else {
    exit('Usuario incorrecto!');
}

$stmt->close();
$conexion->close();
?>
