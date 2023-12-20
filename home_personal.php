<?php
session_start();
include 'db_connect.php';
// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['user']; 

$sql = "SELECT * FROM pacientes";
$result = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="appbar">
        <div class="appbar-left">
            Bienvenido, <?php echo htmlspecialchars($username); ?>
        </div>
        <div class="appbar-right">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
        <div class="grid-container">
            <!-- Encabezados de la cuadrícula -->
            <div class="grid-item header">
                <div class="grid-item-content">
                    <strong>Usuario</strong>
                </div>
                <div class="grid-item-content">
                    <strong>Email</strong>
                </div>
            </div>

            <!-- Datos de la cuadrícula -->
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="grid-item">
                        <div class="grid-item-content">
                            <?php echo htmlspecialchars($row["username"]); ?>
                        </div>
                        <div class="grid-item-content">
                            <?php echo htmlspecialchars($row["email"]); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron pacientes.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>