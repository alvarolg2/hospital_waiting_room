<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

$job_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

if ($job_id) {
    $stmt = $connection->prepare("SELECT * FROM job WHERE job_id = ?");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $msg = "No se encontró el job con el ID especificado.";
        $row = array('name' => '');  // Asegúrate de que la variable $row esté definida incluso si no se encontraron resultados
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_id = $_POST['job_id'];
    $name = $_POST['name'];

    $stmt = $connection->prepare("UPDATE job SET name = ? WHERE job_id = ?");
    $stmt->bind_param("si", $name, $job_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Datos actualizados con éxito.";
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
    <title>Editar job</title>
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
        <h2>Editar job</h2>
        <?php if ($msg): ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>
        <form action="edit_job.php?id=<?php echo $job_id; ?>" method="post">
            <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

            <div class="input-group">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>

            <div class="input-group">
                <button type="submit" class="submit-button">Guardar</button>
            </div>
        </form>
    </div>
</body>
</html>