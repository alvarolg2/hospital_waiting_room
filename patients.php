<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

$query = "SELECT patients.*, (SELECT COUNT(*) FROM appointments WHERE Patients_patients_id = patients.patients_id AND status = 'pending') as tiene_appointment 
          FROM patients 
          WHERE is_deleted = 0";
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
            <strong>Usuario</strong>
        </div>
        <div class="grid-item-content">
            <strong>Email</strong>
        </div>
        <div class="grid-item-content">
            <strong>Acciones</strong>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="grid-item">
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["username"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["email"]); ?>
                </div>
                <div class="grid-item-content action-icons">
                    <?php if ($row['tiene_appointment'] > 0): ?>
                        <a title="Este patient ya tiene una appointment">⏰</a>
                    <?php else: ?>
                        <a href="create_appointment.php?patient_id=<?php echo $row['patients_id']; ?>" title="Crear Appointment">📅</a>
                    <?php endif; ?>
                    <a href="edit_patient.php?id=<?php echo $row["patients_id"]; ?>" title="Editar">✏️</a>
                    <a href="delete_patients.php?id=<?php echo $row["patients_id"]; ?>" title="Eliminar" onclick="return confirmDelete()">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de patients.</p>
    <?php endif; ?>
</div>
<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar a este patient?");
}
</script>
</body>
</html>
