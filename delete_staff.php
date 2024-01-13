<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

// Comprueba si se ha enviado el ID del staff
if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    // Prepara la consulta SQL para evitar inyecciones SQL
    // Esta consulta actualizará el campo 'is_deleted' en lugar de eliminar el registro
    $stmt = $connection->prepare("UPDATE staff SET is_deleted = 1 WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Registro de staff eliminado con éxito.";
    } else {
        echo "Error al elminar el registro: " . $connection->error;
    }

    // Cierra la declaración
    $stmt->close();
} else {
    echo "No se ha proporcionado ID del staff.";
}

header("Location: home_admin.php");
exit();
?>