<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user'];
$msg = '';

// Obtener el ID de la appointment a mostrar
$appointments_id = isset($_GET['id']) ? $_GET['id'] : '';

// Consultar los datos actuales de la appointment
if ($appointments_id) {
    $stmt = $connection->prepare("SELECT * FROM appointments WHERE appointments_id = ?");
    $stmt->bind_param("i", $appointments_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $urgency_id = $row['Urgency_category_id'];
        $urgency_reason = $row['urgency_reason'];
        $observations = $row['observations'];
        $medication = $row['medication'];  
        $create_time = $row['create_time']; 
        $finish_time = $row['finish_time']; 
    }
    $stmt->close();
}

// Consultar las urgency disponibles
$urgencys = array();
$queryUrgency = "SELECT * FROM urgency";
$resultUrgency = $connection->query($queryUrgency);
if ($resultUrgency) {
    while ($row = $resultUrgency->fetch_assoc()) {
        $urgencys[] = $row;
    }
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointments_id = $_POST['appointments_id'];
    $urgency_id = $_POST['urgency_id'];
    $urgency_reason = $_POST['urgency_reason'];
    $observations = $_POST['observations'];

    $stmt = $connection->prepare("UPDATE appointments SET Urgency_category_id = ?, urgency_reason = ?, observations = ? WHERE appointments_id = ?");
    $stmt->bind_param("issi", $urgency_id, $urgency_reason, $observations, $appointments_id);

    if ($stmt->execute()) {
        $msg = "Appointment actualizada con éxito.";
        header("Location: home_staff.php");
        exit();
    } else {
        $msg = "Error al actualizar la appointment: " . $connection->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver detalles de la cita</title>
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
        <h2>Detalles de la cita</h2>
        <?php if ($msg): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <div class="input-group">
            <label><strong>Motivo de la urgencia:</strong></label>
            <p><?php echo htmlspecialchars($urgency_reason); ?></p>
        </div>
        <div class="input-group">
            <label><strong>Observaciones:</strong></label>
            <p><?php echo htmlspecialchars($observations); ?></p>
        </div>
        <div class="input-group">
            <label><strong>Medicación:</strong></label>
            <p><?php echo htmlspecialchars($medication); ?></p>
        </div>
        <div class="input-group">
            <label><strong>Urgencia:</strong></label>
            <?php foreach ($urgencys as $urgency): ?>
                <?php if ($urgency['urgency_id'] == $urgency_id): ?>
                    <p><?php echo htmlspecialchars($urgency['name']); ?></p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="input-group">
            <label><strong>Fecha de Creación:</strong></label>
            <p><?php echo htmlspecialchars($create_time); ?></p>
        </div>
        <?php if ($finish_time): ?>
            <div class="input-group">
                <label><strong>Fecha de Finalización:</strong></label>
                <p><?php echo htmlspecialchars($finish_time); ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>


