<?php
session_start();
include 'db_connect.php';
// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

$staff_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

$jobs = array();
$queryJobs = "SELECT * FROM job"; // Asegúrate de que los nombres de columna y tabla sean correctos
$resultJobs = $connection->query($queryJobs);

if ($resultJobs) {
    while ($job = $resultJobs->fetch_assoc()) {
        $jobs[] = $job;
    }
}

// Consultar los datos actuales del staff
if ($staff_id) {
    $stmt = $connection->prepare("SELECT * FROM staff WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = $_POST['staff_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $job_id = $_POST['job_id'];

    $stmt = $connection->prepare("UPDATE staff SET username = ?, email = ?, Job_job_id = ? WHERE staff_id = ?");
    $stmt->bind_param("ssii", $username, $email, $job_id, $staff_id);

    if ($stmt->execute()) {
        $msg = "Datos actualizados con éxito.";
        // Vuelve a cargar los datos actualizados
        header("Location: home_admin.php");
        exit();
    } else {
        $msg = "Error al actualizar los datos: " . $connection->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Staff</title>
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
        <h2>Editar Staff</h2>
        <?php if ($msg): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="edit_staff.php" method="post">
            <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">

            <div class="input-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="job_id">Job:</label>
                <select id="job_id" name="job_id" required>
                    <?php foreach ($jobs as $job): ?>
                        <option value="<?php echo $job['job_id']; ?>" <?php echo $job['job_id'] == $row['Job_job_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($job['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <button type="submit" class="submit-button">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>
