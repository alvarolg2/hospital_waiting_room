<?php
include 'db_connect.php';

// Comprueba si se ha enviado el ID del staff
if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    $stmt = $connection->prepare("UPDATE staff SET is_deleted = 1 WHERE staff_id = ?");
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        echo "Registro de staff eliminado con éxito.";
    } else {
        echo "Error al elminar el registro: " . $connection->error;
    }

    $stmt->close();
} else {
    echo "No se ha proporcionado ID del staff.";
}

header("Location: home_admin.php");
exit();
?>