<?php
include 'db_connect.php';

if(isset($_POST['appointmentId'])) {
    $appointmentId = $_POST['appointmentId'];

    $query = "SELECT calling_patient FROM appointments WHERE appointments_id = $appointmentId";
    $result = mysqli_query($connection, $query);
    $appointment = mysqli_fetch_assoc($result);

    echo $appointment['calling_patient']; 
}
?>
