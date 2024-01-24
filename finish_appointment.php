<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $observations = $_POST['observations'];
    $medication = $_POST['medication'];
    $finish_time = date('Y-m-d H:i:s'); // Fecha y hora actual

    // Actualizar la cita en la base de datos
    $updateQuery = "UPDATE appointments SET observations = ?, medication = ?, finish_time = ? WHERE appointments_id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("sssi", $observations, $medication, $finish_time, $appointment_id);
    if ($updateStmt->execute()) {
        echo "Cita actualizada con éxito.";
        header("Location: home_doctor.php");
    } else {
        echo "Error al actualizar la cita.";
        header("Location: home_doctor.php");
    }
    $updateStmt->close();
} else {
    echo "Método de solicitud no válido.";
    header("Location: home_doctor.php");
}
?>
