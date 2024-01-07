<?php
include 'db_connect.php'; 

$query = "SELECT * FROM urgencia"; 
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
    <div class="grid-item header">
        <div class="grid-item-content">
            <strong>Nombre</strong>
        </div>
        <div class="grid-item-content">
            <strong>Prioridad</strong>
        </div>
        <div class="grid-item-content">
            <strong>Acciones</strong>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="grid-item">
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["name"]); ?>
                </div>
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["priority"]); ?>
                </div>
                <div class="grid-item-content action-icons">
                    <a href="edit_urgencia.php?id=<?php echo $row["urgencia_id"]; ?>" title="Editar urgencia">✏️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de urgencia.</p>
    <?php endif; ?>
</div>
</body>
</html>