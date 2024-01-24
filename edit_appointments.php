<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user'];
$msg = '';

// Obtener el ID de la appointment a editar
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

    // Actualizar la base de datos
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
    <title>Editar cita</title>
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
    <h2>Editar cita</h2>
    <?php if ($msg): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
    <form action="edit_appointments.php?id=<?php echo htmlspecialchars($appointments_id); ?>" method="post">
        <input type="hidden" name="appointments_id" value="<?php echo htmlspecialchars($appointments_id); ?>">

        <div class="input-group">
            <label for="urgency_reason">Motivo de la urgencia:</label>
            <textarea id="urgency_reason" name="urgency_reason" rows="4" cols="50"><?php echo htmlspecialchars($urgency_reason); ?></textarea>
        </div>

        <div class="input-group">
            <label for="observations">Observaciones:</label>
            <textarea id="observations" name="observations" rows="4" cols="50"><?php echo htmlspecialchars($observations); ?></textarea>
        </div>

        <div class="input-group">
            <label for="urgency_id">Urgencia:</label>
            <select id="urgency_id" name="urgency_id">
                <?php foreach ($urgencys as $urgency): ?>
                    <option value="<?php echo $urgency['urgency_id']; ?>" <?php if ($urgency['urgency_id'] == $urgency_id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($urgency['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="input-group">
            <button type="submit" class="submit-button">Guardar Cambios</button>
        </div>
    </form>
</div>
</body>
</html>
