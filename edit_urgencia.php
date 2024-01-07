<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

$urgencia_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

if ($urgencia_id) {
    $stmt = $conexion->prepare("SELECT * FROM urgencia WHERE urgencia_id = ?");
    $stmt->bind_param("i", $urgencia_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $msg = "No se encontró la urgencia con el ID especificado.";
        $row = array('name' => '');  // Asegúrate de que la variable $row esté definida incluso si no se encontraron resultados
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urgencia_id = $_POST['urgencia_id'];
    $name = $_POST['name'];

    $stmt = $conexion->prepare("UPDATE urgencia SET name = ? WHERE urgencia_id = ?");
    $stmt->bind_param("si", $name, $urgencia_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Datos actualizados con éxito.";
        header("Location: home_admin.php");
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
    <title>Editar Urgencia</title>
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
        <h2>Editar Urgencia</h2>
        <?php if ($msg): ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>
        <form action="edit_urgencia.php?id=<?php echo $urgencia_id; ?>" method="post">
            <input type="hidden" name="urgencia_id" value="<?php echo $urgencia_id; ?>">

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
