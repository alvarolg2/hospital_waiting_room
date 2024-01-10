<?php
include 'db_connect.php'; // Asegúrate de que este archivo contiene la lógica de conexión a tu base de datos

// Comprueba si se ha enviado el ID del personal
if (isset($_GET['id'])) {
    $pacientes_id = $_GET['id'];

    // Prepara la consulta SQL para evitar inyecciones SQL
    // Esta consulta actualizará el campo 'is_deleted' en lugar de eliminar el registro
    $stmt = $conexion->prepare("UPDATE pacientes SET is_deleted = 1 WHERE pacientes_id = ?");
    $stmt->bind_param("i", $pacientes_id);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Registro de paciente eliminado con éxito.";
    } else {
        echo "Error al elminar el registro: " . $conexion->error;
    }

    // Cierra la declaración
    $stmt->close();
} else {
    echo "No se ha proporcionado ID del paciente.";
}

header("Location: home_personal.php");
exit();
?>