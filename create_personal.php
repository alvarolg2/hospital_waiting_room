<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}
$user = $_SESSION['user'];
$msg = '';

$puestos = array();
$query = "SELECT * FROM puesto"; // Asegúrate de que los nombres de columna y tabla sean correctos
$result = $conexion->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $puestos[] = $row;
    }
}

// Procesar el formulario al recibir los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $puesto_id = $_POST['puesto_id'];

    // Agregar lógica para validar los datos aquí (importante para seguridad)

    // Insertar los datos en la base de datos
    $stmt = $conexion->prepare("INSERT INTO personal (username, password, email, Puesto_puesto_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $username, $password, $email, $puesto_id);

    if ($stmt->execute()) {
        $msg = "Personal creado con éxito.";
        header("Location: home_admin.php");
    } else {
        $msg = "Error al crear el personal: " . $conexion->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Personal</title>
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
    <h2>Crear Personal</h2>
    <?php if ($msg): ?>
        <p><?php echo $msg; ?></p>
    <?php endif; ?>
    <form action="create_personal.php" method="post">
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
            <label for="puesto_id">Puesto:</label>
            <select id="puesto_id" name="puesto_id" required>
                <?php foreach ($puestos as $puesto): ?>
                    <option value="<?php echo $puesto['puesto_id']; ?>">
                        <?php echo htmlspecialchars($puesto['name']); ?>
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
