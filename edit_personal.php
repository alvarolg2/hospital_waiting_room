<?php
session_start();
include 'db_connect.php';
// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$user = $_SESSION['user']; 

$personal_id = isset($_GET['id']) ? $_GET['id'] : '';
$msg = '';

$puestos = array();
$queryPuestos = "SELECT * FROM puesto"; // Asegúrate de que los nombres de columna y tabla sean correctos
$resultPuestos = $conexion->query($queryPuestos);

if ($resultPuestos) {
    while ($puesto = $resultPuestos->fetch_assoc()) {
        $puestos[] = $puesto;
    }
}

// Consultar los datos actuales del personal
if ($personal_id) {
    $stmt = $conexion->prepare("SELECT * FROM personal WHERE personal_id = ?");
    $stmt->bind_param("i", $personal_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
}

// Procesar la actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $personal_id = $_POST['personal_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $puesto_id = $_POST['puesto_id'];

    $stmt = $conexion->prepare("UPDATE personal SET username = ?, email = ?, Puesto_puesto_id = ? WHERE personal_id = ?");
    $stmt->bind_param("ssii", $username, $email, $puesto_id, $personal_id);

    if ($stmt->execute()) {
        $msg = "Datos actualizados con éxito.";
        // Vuelve a cargar los datos actualizados
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
        <h2>Editar Personal</h2>
        <?php if ($msg): ?>
            <p><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="edit_personal.php" method="post">
            <input type="hidden" name="personal_id" value="<?php echo $personal_id; ?>">

            <div class="input-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
            </div>
            <div class="input-group">
                <label for="puesto_id">Puesto:</label>
                <select id="puesto_id" name="puesto_id" required>
                    <?php foreach ($puestos as $puesto): ?>
                        <option value="<?php echo $puesto['puesto_id']; ?>" <?php echo $puesto['puesto_id'] == $row['Puesto_puesto_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($puesto['name']); ?>
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
