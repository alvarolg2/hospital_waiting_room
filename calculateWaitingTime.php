<?php
session_start();
include 'db_connect.php';

if (isset($_POST['appointmentId'])) {
    $appointmentId = $_POST['appointmentId'];
    echo calculateEstimatedTime($appointmentId, $connection);
}
?>