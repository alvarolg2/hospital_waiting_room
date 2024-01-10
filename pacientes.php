<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

$query = "SELECT * FROM pacientes 
          WHERE is_deleted = 0";
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
                    <a href="edit_paciente.php?id=<?php echo $row["pacientes_id"]; ?>" title="Editar">✏️</a>
                    <a href="delete_paciente.php?id=<?php echo $row["pacientes_id"]; ?>" title="Eliminar" onclick="return confirmDelete()">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de personal.</p>
    <?php endif; ?>
</div>
<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar a este paciente?");
}
</script>
</body>
</html>