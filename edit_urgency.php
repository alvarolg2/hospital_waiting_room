<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user = $_SESSION['user'];

$urgency_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

if ($urgency_id) {
    $stmt = $connection->prepare("SELECT * FROM urgency WHERE urgency_id = ?");
    $stmt->bind_param("i", $urgency_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        $msg = "No se encontró la urgency con el ID especificado.";
        $row = array('name' => '');  // Asegúrate de que la variable $row esté definida incluso si no se encontraron resultados
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urgency_id = $_POST['urgency_id'];
    $name = $_POST['name'];

    $stmt = $connection->prepare("UPDATE urgency SET name = ? WHERE urgency_id = ?");
    $stmt->bind_param("si", $name, $urgency_id);

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
    <title>Editar urgencia</title>
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
        <h2>Editar urgencia</h2>
        <?php if ($msg): ?>
            <div class="alert"><?php echo $msg; ?></div>
        <?php endif; ?>
        <form action="edit_urgency.php?id=<?php echo $urgency_id; ?>" method="post">
            <input type="hidden" name="urgency_id" value="<?php echo $urgency_id; ?>">

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
