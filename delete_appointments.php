<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

// Comprobar si se ha proporcionado el ID de la appointment
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $connection->prepare("DELETE FROM appointments WHERE appointments_id = ?");
    $stmt->bind_param("i", $appointmentId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Appointment eliminada con éxito.";
    } else {
        echo "Error al eliminar la appointment: " . $connection->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    echo "No se ha proporcionado un ID de appointment.";
}

// Redirigir de vuelta a la página de appointments
header("Location: home_staff.php");
exit();

?>
