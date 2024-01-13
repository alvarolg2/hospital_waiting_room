<?php
session_start();
include 'db_connect.php';

$role = $_POST['role'];
$username = $_POST['username'];
$password = $_POST['password'];

// Diferenciar la consulta SQL según el rol del usuario
if ($role == 'staff') {
    // Consulta para el staff
    $query = "SELECT staff.*, job.name as job_name FROM staff 
              LEFT JOIN job ON staff.Job_job_id = job.job_id 
              WHERE username = '$username' and password = '$password'";
} else {
    // Consulta para patients
    $query = "SELECT * FROM patients 
              WHERE username = '$username' and password = '$password'";
}

$validate_user = mysqli_query($connection, $query);
if (!$validate_user) {
    exit('Usuario incorrecto!');
}

if ($usuario = mysqli_fetch_assoc($validate_user)) {
    // Almacenar el nombre de usuario y el ID en la sesión
    $_SESSION['user'] = $username;

    if ($role == 'staff') {
        // Lógica para staff
        $_SESSION['user_id'] = $usuario['staff_id']; 
        $_SESSION['super_user'] = $usuario['super_user'];

        // Verificar si el usuario es un superUser
        if ($_SESSION['super_user']) {
            header("location:home_admin.php");
            exit;
        }

        $job = strtolower($usuario['job_name'] ?? '');
        switch ($job) {
            case 'medico':
                header("location:home_doctor.php");
                break;
            case 'recepcionista':
                header("location:home_staff.php");
                break;
            default:
                header("location:home_admin.php");
                break;
        }
    } else {
        $_SESSION['user_id'] = $usuario['patients_id'];
        // Lógica para patients
        header("location:home_patients.php");
    }
    exit;
} else {
    exit('Usuario o contraseña incorrectos');
}

$connection-> close();
?>