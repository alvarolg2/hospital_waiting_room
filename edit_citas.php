<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user'];
$msg = '';

// Obtener el ID de la cita a editar
$citas_id = isset($_GET['id']) ? $_GET['id'] : '';

// Consultar los datos actuales de la cita
if ($citas_id) {
    $stmt = $conexion->prepare("SELECT * FROM citas WHERE citas_id = ?");
    $stmt->bind_param("i", $citas_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $citas_id = $_POST['citas_id'];
    // Asume otros campos como urgencia_id, personal_id, etc.
    $urgencia_id = $_POST['urgencia_id'];
    $personal_id = $_POST['personal_id'];
    $pacientes_id = $_POST['pacientes_id'];
    $status = $_POST['status'];

    // Actualizar la base de datos
    $stmt = $conexion->prepare("UPDATE citas SET Urgencia_category_id = ?, Personal_personal_id = ?, Pacientes_pacientes_id = ?, status = ? WHERE citas_id = ?");
    $stmt->bind_param("iiisi", $urgencia_id, $personal_id, $pacientes_id, $status, $citas_id);

    if ($stmt->execute()) {
        $msg = "Cita actualizada con éxito.";
        header("Location: home_personal.php"); // Redirigir de vuelta a la lista de citas
        exit();
    } else {
        $msg = "Error al actualizar la cita: " . $conexion->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cita</title>
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
        <h2>Editar Cita</h2>
        <?php if ($msg): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="edit_citas.php?id=<?php echo $citas_id; ?>" method="post">
            <input type="hidden" name="citas_id" value="<?php echo $citas_id; ?>">

            <!-- Asume campos como urgencia_id, personal_id, etc. -->
            <!-- Agrega campos de formulario aquí según sea necesario -->
            
            <div class="input-group">
                <button type="submit" class="submit-button">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
