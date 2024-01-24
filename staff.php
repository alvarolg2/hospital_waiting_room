<?php
include 'db_connect.php'; 

$query = "SELECT staff.staff_id as staff_id, staff.username, staff.email, job.name AS job_nombre FROM staff 
          JOIN job ON staff.Job_job_id = job.job_id
          WHERE staff.is_deleted = 0";
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
            <strong>Usuario</strong>
        </div>
        <div class="grid-item-content">
            <strong>Email</strong>
        </div>
        <div class="grid-item-content">
            <strong>Job</strong>
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
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["job_nombre"]); ?>
                </div>
                <div class="grid-item-content action-icons">
                    <a href="edit_staff.php?id=<?php echo $row["staff_id"]; ?>" title="Editar">✏️</a>
                    <a href="delete_staff.php?id=<?php echo $row["staff_id"]; ?>" title="Eliminar" onclick="return confirmDelete()">🗑️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de staff.</p>
    <?php endif; ?>
</div>
<script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar a este miembro del staff?");
}
</script>
</body>
</html>