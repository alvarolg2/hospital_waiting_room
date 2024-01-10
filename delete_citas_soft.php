<?php
session_start();
include 'db_connect.php';

// Comprueba si el usuario está logueado y si tiene permisos para realizar un borrado suave
if (!isset($_SESSION['user']) || !esUsuarioAutorizadoParaBorrar()) {
    header('Location: login.php'); 
    exit();
}

// Comprobar si se ha proporcionado el ID de la cita
if (isset($_GET['id'])) {
    $citaId = $_GET['id'];

    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $conexion->prepare("UPDATE citas SET is_deleted = 1 WHERE citas_id = ?");
    $stmt->bind_param("i", $citaId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Cita marcada como eliminada con éxito.";
    } else {
        echo "Error al marcar la cita como eliminada: " . $conexion->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    echo "No se ha proporcionado un ID de cita.";
}

// Redirigir de vuelta a la página de citas
header("Location: citas.php");
exit();

// Función para verificar si el usuario tiene permisos para realizar un borrado suave
function esUsuarioAutorizadoParaBorrar() {
    // Implementa tu lógica para verificar si el usuario tiene permisos
    // Por ejemplo, verificar si es un superusuario o tiene un rol específico
    return true; // Cambiar según la lógica de tu aplicación
}
?>
