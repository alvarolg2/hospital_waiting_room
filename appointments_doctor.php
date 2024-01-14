<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

// Consulta para obtener las appointments
$query = "SELECT appointments.*, urgency.name AS urgency_name, urgency.priority AS urgency_priority, staff.username AS staff_name, patients.username AS patient_name 
          FROM appointments
          JOIN urgency ON appointments.Urgency_category_id = urgency.urgency_id
          JOIN staff ON appointments.Staff_staff_id = staff.staff_id
          JOIN patients ON appointments.Patients_patients_id = patients.patients_id
          WHERE appointments.status != 'completed'
          ORDER BY urgency.priority ASC, appointments.create_time ASC";
$result = $connection->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Staff</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
<div class="grid-container">
    <div class="grid-item header">
        <div class="grid-item-content">
            <strong>Patient</strong>
        </div>
        <div class="grid-item-content">
            <strong>Medico</strong>
        </div>
        <div class="grid-item-content">
            <strong>Urgency</strong>
        </div>
        <div class="grid-item-content">
            <strong>Estado</strong>
        </div>
        <div class="grid-item-content">
            <strong>Acciones</strong>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="grid-item">
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["patient_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["staff_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["urgency_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["status"]); ?>
                </div>
                <div class="grid-item-content action-icons">
                    <!-- Botón para llamar al patient (ejemplo, utilizando JavaScript) -->
                    <a href="call_patient.php?id=<?php echo $row['appointments_id']; ?>" title="Llamar paciente">📞</a>
                    
                    <!-- Botón para ver detalles de la appointment -->
                    <a href="show_appointment.php?id=<?php echo $row['appointments_id']; ?>" title="Ver cita">👁️</a>

                    <!-- Botón para editar la appointment (opcional si no se necesita editar) -->
                    <!-- <a href="edit_appointments.php?id=<?php echo $row['appointments_id']; ?>" title="Editar">✏️</a> -->
                    
                    <!-- Botón para eliminar la appointment -->
                    <a href="delete_appointments.php?id=<?php echo $row['appointments_id']; ?>" title="Eliminar cita" onclick="return confirmDelete()">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de appointments.</p>
    <?php endif; ?>
</div>
<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar esta appointment?");
}

function callPatient(appointmentId) {
    // Aquí puedes agregar la lógica para llamar al patient
    // Por ejemplo, mostrar una notificación, abrir una nueva ventana, etc.
    alert("Llamando al patient de la appointment " + appointmentId);
}
</script>
</body>
</html>