<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $patients_id = $_GET['id'];

    // Primero, comprobar si el paciente tiene citas finalizadas
    $checkStmt = $connection->prepare("SELECT COUNT(*) FROM appointments WHERE Patients_patients_id = ? AND status = 'completed'");
    $checkStmt->bind_param("i", $patients_id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_array();
    $finished_appointments = $row[0];
    $checkStmt->close();

    // Decidir si eliminar o marcar como eliminado
    if ($finished_appointments > 0) {
        // Marcar como eliminado
        $stmt = $connection->prepare("UPDATE patients SET is_deleted = 1 WHERE patients_id = ?");
    } else {
        // Eliminar el registro
        $stmt = $connection->prepare("DELETE FROM patients WHERE patients_id = ?");
    }
    
    $stmt->bind_param("i", $patients_id);
    if ($stmt->execute()) {
        echo $finished_appointments > 0 ? 
            "Registro de paciente marcado como eliminado." : 
            "Registro de paciente eliminado con éxito.";
    } else {
        echo "Error al procesar el registro: " . $connection->error;
    }
    $stmt->close();
} else {
    echo "No se ha proporcionado ID del paciente.";
}

header("Location: home_staff.php");
exit();
?>
