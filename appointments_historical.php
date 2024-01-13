<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

// Consulta para obtener las appointments
$query = "SELECT appointments.*, urgency.name AS urgency_name, staff.username AS staff_name, patients.username AS patient_name 
          FROM appointments
          JOIN urgency ON appointments.Urgency_category_id = urgency.urgency_id
          JOIN staff ON appointments.Staff_staff_id = staff.staff_id
          JOIN patients ON appointments.Patients_patients_id = patients.patients_id
          WHERE appointments.finish_time IS NOT NULL
          ORDER BY appointments.finish_time DESC";
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
    <!-- Encabezados de la cuadrícula -->
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
                    <a href="delete_appointments_soft.php?id=<?php echo $row["appointments_id"]; ?>" title="Eliminar" onclick="return confirmDelete()">🗑️</a>
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
</script>
</body>
</html>
