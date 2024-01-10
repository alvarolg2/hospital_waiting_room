<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

// Consulta para obtener las citas
$query = "SELECT citas.*, urgencia.name AS urgencia_name, urgencia.priority AS urgencia_priority, personal.username AS personal_name, pacientes.username AS paciente_name 
          FROM citas
          JOIN urgencia ON citas.Urgencia_category_id = urgencia.urgencia_id
          JOIN personal ON citas.Personal_personal_id = personal.personal_id
          JOIN pacientes ON citas.Pacientes_pacientes_id = pacientes.pacientes_id
          ORDER BY urgencia.priority ASC, citas.create_time ASC";
$result = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Personal</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
<div class="grid-container">
    <!-- Encabezados de la cuadrícula -->
    <div class="grid-item header">
        <div class="grid-item-content">
            <strong>Paciente</strong>
        </div>
        <div class="grid-item-content">
            <strong>Medico</strong>
        </div>
        <div class="grid-item-content">
            <strong>Urgencia</strong>
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
                    <?php echo htmlspecialchars($row["paciente_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["personal_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["urgencia_name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["status"]); ?>
                </div>
                <div class="grid-item-content action-icons">
                    <a href="edit_citas.php?id=<?php echo $row["citas_id"]; ?>" title="Editar">✏️</a>
                    <a href="delete_citas.php?id=<?php echo $row["citas_id"]; ?>" title="Eliminar" onclick="return confirmDelete()">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de citas.</p>
    <?php endif; ?>
</div>
<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar esta cita?");
}
</script>
</body>
</html>
