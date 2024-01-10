<?php
session_start();
include 'db_connect.php';

if (isset($_POST['citaId'])) {
    $citaId = $_POST['citaId'];
    echo calcularTiempoEstimado($citaId, $conexion);
}
?>