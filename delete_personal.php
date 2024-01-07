<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

// Comprueba si se ha enviado el ID del personal
if (isset($_GET['id'])) {
    $personal_id = $_GET['id'];

    // Prepara la consulta SQL para evitar inyecciones SQL
    // Esta consulta actualizará el campo 'is_deleted' en lugar de eliminar el registro
    $stmt = $conexion->prepare("UPDATE personal SET is_deleted = 1 WHERE personal_id = ?");
    $stmt->bind_param("i", $personal_id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Registro de personal eliminado con éxito.";
    } else {
        echo "Error al elminar el registro: " . $conexion->error;
    }

    // Cierra la declaración
    $stmt->close();
} else {
    echo "No se ha proporcionado ID del personal.";
}

header("Location: home_admin.php");
exit();
?>