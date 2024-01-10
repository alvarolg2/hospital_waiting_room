<?php
session_start();
include 'db_connect.php';

// Comprueba si el usuario está logueado y si es un usuario con permisos para eliminar citas
if (!isset($_SESSION['user']) || !esUsuarioAutorizadoParaEliminar()) {
    header('Location: login.php'); 
    exit();
}

// Comprobar si se ha proporcionado el ID de la cita
if (isset($_GET['id'])) {
    $citaId = $_GET['id'];

    // Preparar la consulta SQL para evitar inyecciones SQL
    $stmt = $conexion->prepare("DELETE FROM citas WHERE citas_id = ?");
    $stmt->bind_param("i", $citaId);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Cita eliminada con éxito.";
    } else {
        echo "Error al eliminar la cita: " . $conexion->error;
    }

    // Cerrar la declaración preparada
    $stmt->close();
} else {
    echo "No se ha proporcionado un ID de cita.";
}

// Redirigir de vuelta a la página de citas
header("Location: home_personal.php");
exit();

// Función para verificar si el usuario tiene permisos para eliminar citas
function esUsuarioAutorizadoParaEliminar() {
    // Implementa tu lógica para verificar si el usuario tiene permisos
    // Por ejemplo, verificar si es un superusuario o tiene un rol específico
    return true; // Cambiar según la lógica de tu aplicación
}
?>
