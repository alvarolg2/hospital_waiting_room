<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}
$user = $_SESSION['user'];
$msg = '';

$jobs = array();
$query = "SELECT * FROM job";
$result = $connection->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
}

// Procesar el formulario al recibir los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $job_id = $_POST['job_id'];

    // Insertar los datos en la base de datos
    $stmt = $connection->prepare("INSERT INTO staff (username, password, email, Job_job_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $password, $email, $job_id);

    if ($stmt->execute()) {
        $msg = "Staff creado con éxito.";
        header("Location: home_admin.php");
    } else {
        $msg = "Error al crear el staff: " . $connection->error;
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
<div class="form-container">
    <h2>Crear personal</h2>
    <?php if ($msg): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
    <form action="create_staff.php" method="post">
        <div class="input-group">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="input-group">
            <label for="job_id">Trabajo:</label>
            <select id="job_id" name="job_id" required>
                <?php foreach ($jobs as $job): ?>
                    <option value="<?php echo $job['job_id']; ?>">
                        <?php echo htmlspecialchars($job['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input-group">
            <button type="submit" class="submit-button">Crear</button>
        </div>
    </form>
</div>
</body>
</html>
