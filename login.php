<?php
session_start();
include 'db_connect.php';

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password'];

// Diferenciar la consulta SQL según el rol del usuario
if ($role == 'personal') {
    // Consulta para el personal
    $query = "SELECT personal.*, puesto.name as puesto_name FROM personal 
              LEFT JOIN puesto ON personal.Puesto_puesto_id = puesto.puesto_id 
              WHERE username = '$username' and password = '$password'";
} else {
    // Consulta para pacientes
    $query = "SELECT * FROM pacientes 
              WHERE username = '$username' and password = '$password'";
}

$validate_user = mysqli_query($conexion, $query);
if (!$validate_user) {
    exit('Usuario incorrecto!');
}

if ($usuario = mysqli_fetch_assoc($validate_user)) {
    $_SESSION['user'] = $username;

    if ($role == 'personal') {
        // Lógica para personal
        $_SESSION['super_user'] = $usuario['super_user'];

        // Verificar si el usuario es un superUser
        if ($_SESSION['super_user']) {
            header("location:home_admin.php");
            exit;
        }

        $puesto = strtolower($usuario['puesto_name'] ?? '');
        switch ($puesto) {
            case 'medico':
                header("location:home_medico.php");
                break;
            case 'recepcionista':
                header("location:home_recepcionista.php");
                break;
            default:
                header("location:home_admin.php");
                break;
        }
    } else {
        // Lógica para pacientes
        header("location:home_pacientes.php");
    }
    exit;
} else {
    exit('Usuario o contraseña incorrectos');
}

$conexion->close();
?>
