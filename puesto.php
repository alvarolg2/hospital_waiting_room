<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

$query = "SELECT * FROM puesto"; // Asume una tabla 'personal'
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
            <strong>Nombre</strong>
        </div>
        <div class="grid-item-content">
            <strong>Acciones</strong>
        </div>
    </div>
    <!-- Datos de la cuadrícula -->
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="grid-item">
                <div class="grid-item-content">
                    <?php echo htmlspecialchars($row["name"]); ?>
                </div>
                <!-- Columna de acciones con emojis en línea horizontal -->
                <div class="grid-item-content action-icons">
                    <a href="edit_puesto.php?id=<?php echo $row["puesto_id"]; ?>" title="Editar personal">✏️</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No se encontraron datos de personal.</p>
    <?php endif; ?>
</div>

</body>
</html>

