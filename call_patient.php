<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Verificar si se pasó el ID de la cita
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Actualizar el campo call_patient en la base de datos
    $updateQuery = "UPDATE appointments SET calling_patient = 1 WHERE appointments_id = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("i", $appointment_id);
    $updateStmt->execute();
    $updateStmt->close();

    // Preparar y ejecutar la consulta para obtener los detalles de la cita
    $query = "SELECT appointments.*, 
                     urgency.name AS urgency_name, 
                     staff.username AS staff_name, 
                     patients.username AS patient_name 
              FROM appointments
              LEFT JOIN urgency ON appointments.Urgency_category_id = urgency.urgency_id
              LEFT JOIN staff ON appointments.Staff_staff_id = staff.staff_id
              LEFT JOIN patients ON appointments.Patients_patients_id = patients.patients_id
              WHERE appointments.appointments_id = ?";

    $stmt = $connection->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();
    } else {
        echo "Cita no encontrada.";
        exit;
    }
} else {
    echo "No se especificó el ID de la cita.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Cita</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<form action="finish_appointment.php" method="post">
    <div class="form-container">
        <h2>Detalles de la Cita</h2>
        <input type="hidden" name="appointment_id" value="<?php echo $appointment_id; ?>">
        <p><strong>Paciente:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
        <p><strong>Medico:</strong> <?php echo htmlspecialchars($appointment['staff_name']); ?></p>
        <p><strong>Urgencia:</strong> <?php echo htmlspecialchars($appointment['urgency_name']); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($appointment['status']); ?></p>
        <p><strong>Motivo de la Urgencia:</strong> <?php echo htmlspecialchars($appointment['urgency_reason']); ?></p>
        <div class="input-group">
            <p><strong>Observaciones:</strong>
            <textarea id="observations" name="observations" rows="4" cols="50"><?php echo htmlspecialchars($appointment['observations']); ?></textarea>
        </div>
        <div class="input-group">
            <p><strong>Tratamiento:</strong>
            <textarea id="medication" name="medication" rows="4" cols="50"><?php echo htmlspecialchars($appointment['medication']); ?></textarea>
        </div>
        <div class="input-group">
            <button type="submit" class="submit-button">Finalizar cita</button>
        </div>
    </div>
</form>
</body>
</html>