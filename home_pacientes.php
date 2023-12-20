<?php
session_start();

// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['user']; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            background-color: #f1f1f1;
            padding: 10px 20px;
        }

        .header-left {
            float: left;
        }

        .header-right {
            float: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            Bienvenido, <?php echo htmlspecialchars($username); ?>
        </div>
        <div class="header-right">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    <div>
        <!-- Contenido de la página -->
    </div>
</body>
</html>