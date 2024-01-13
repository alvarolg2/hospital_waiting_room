<?php
session_start();
include 'db_connect.php';

// Comprueba si el usuario está logueado y si tiene permisos para realizar un borrado suave
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

// Comprobar si se ha proporcionado el ID de la appointment
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $connection->prepare("UPDATE appointments SET is_deleted = 1 WHERE appointments_id = ?");
    $stmt->bind_param("i", $appointmentId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Appointment marcada como eliminada con éxito.";
    } else {
        echo "Error al marcar la appointment como eliminada: " . $connection->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    echo "No se ha proporcionado un ID de appointment.";
}

// Redirigir de vuelta a la página de appointments
header("Location: appointment.php");
exit();

?>
