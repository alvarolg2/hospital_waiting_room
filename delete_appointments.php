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

    $stmt = $connection->prepare("DELETE FROM appointments WHERE appointments_id = ?");
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        echo "Appointment eliminada con éxito.";
    } else {
        echo "Error al eliminar la appointment: " . $connection->error;
    }

    $stmt->close();
} else {
    echo "No se ha proporcionado un ID de appointment.";
}

header("Location: home_staff.php");
exit();

?>
