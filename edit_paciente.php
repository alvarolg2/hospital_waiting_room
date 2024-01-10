<?php
session_start();
include 'db_connect.php';
// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

$pacientes_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

// Consultar los datos actuales del personal
if ($pacientes_id) {
    $stmt = $conexion->prepare("SELECT * FROM pacientes WHERE pacientes_id = ?");
    $stmt->bind_param("i", $pacientes_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pacientes_id = $_POST['pacientes_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $conexion->prepare("UPDATE pacientes SET username = ?, email = ?  WHERE pacientes_id = ?");
    $stmt->bind_param("ssi", $username, $email, $pacientes_id);

    if ($stmt->execute()) {
        $msg = "Datos actualizados con éxito.";
        // Vuelve a cargar los datos actualizados
        header("Location: home_personal.php?tab=citas");
        exit();
    } else {
        $msg = "Error al actualizar los datos: " . $conexion->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Personal</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
<div class="appbar">
    <div class="appbar-left">
        Bienvenido, <?php echo htmlspecialchars($user); ?>
    </div>
    <div class="appbar-right">
        <a href="logout.php">Cerrar sesión</a>
    </div>
</div>
    <div class="form-container">
        <h2>Editar paciente</h2>
        <?php if ($msg): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="edit_paciente.php" method="post">
            <input type="hidden" name="pacientes_id" value="<?php echo $pacientes_id; ?>">

            <div class="input-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="input-group">
                <button type="submit" class="submit-button">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
