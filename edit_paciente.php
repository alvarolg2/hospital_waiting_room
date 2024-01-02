// editar_paciente.php
<?php
session_start();
include 'db_connect.php';

// Comprobar si el ID del paciente está presente
if (!isset($_GET['id'])) {
    // Redireccionar si no hay ID
    header('Location: error_page.php'); // Suponiendo que tienes una página de error
    exit();
}

$id_paciente = $_GET['id'];

// Aquí podrías recuperar los datos del paciente de la base de datos
// ...

?>
<!-- Aquí va el HTML para mostrar el formulario de edición -->
