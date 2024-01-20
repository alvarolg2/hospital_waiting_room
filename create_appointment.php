<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}
$user = $_SESSION['user'];
$user_id = $_SESSION['user_id'];
$msg = '';

// Urgency
$urgencys = array();
$queryUrgency = "SELECT * FROM urgency";
$resultUrgency = $connection->query($queryUrgency);
if ($resultUrgency) {
    while ($row = $resultUrgency->fetch_assoc()) {
        $urgencys[] = $row;
    }
}

// Obtener el patient_id de la URL
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : 0;

// Procesar el formulario al recibir los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $urgency_id = $_POST['urgency_id'];
    $urgency_reason = $_POST['urgency_reason'];
    $observations = $_POST['observations'];

    // Insertar los datos en la base de datos
    $stmt = $connection->prepare("INSERT INTO appointments (Patients_patients_id,Staff_staff_id ,Urgency_category_id, urgency_reason, observations) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $patient_id, $user_id, $urgency_id, $urgency_reason, $observations);

    if ($stmt->execute()) {
        $msg = "Appointment creada con éxito.";
        // Redirige a la página después de crear la appointment
        header("Location: home_staff.php");
    } else {
        $msg = "Error al crear la appointment: " . $connection->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear personal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<div class="appbar">
        <div class="appbar-left">
            Bienvenido, <?php echo htmlspecialchars($user); ?>
        </div>
        <div class="appbar-right">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
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
        <h2>Crear cita para paciente</h2>

        <!-- Muestra mensajes de éxito o error -->
        <?php if ($msg): ?>
            <div class="alert <?php echo strpos($msg, 'Error') !== false ? 'alert-error' : 'alert-success'; ?>">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form action="create_appointment.php" method="post">
            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">

            <div class="input-group">
                <label for="urgency_reason">Motivo de la urgencia:</label>
                <textarea id="urgency_reason" name="urgency_reason" rows="4" cols="50" class="text-input"></textarea>
            </div>

            <div class="input-group">
                <label for="observations">Observaciones:</label>
                <textarea id="observations" name="observations" rows="4" cols="50" class="text-input"></textarea>
            </div>

            <div class="input-group">
                <label for="urgency_id">Urgencia:</label>
                <select id="urgency_id" name="urgency_id" class="select-input">
                    <?php foreach ($urgencys as $urgency): ?>
                        <option value="<?php echo $urgency['urgency_id']; ?>"><?php echo htmlspecialchars($urgency['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="submit-button">Crear cita</button>
        </form>
    </div>
</body>
</html>
