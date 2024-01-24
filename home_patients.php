<?php
session_start();
include 'db_connect.php';

// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}
function calculateEstimatedTime($appointmentId, $connection) {
    // Calcular la duración media de las appointments completadas en minutos
    $queryPromedio = "SELECT AVG(TIMESTAMPDIFF(MINUTE, create_time, finish_time)) AS duracion_promedio 
                      FROM appointments 
                      WHERE status = 'completed'";
    $resultadoPromedio = mysqli_query($connection, $queryPromedio);
    $filaPromedio = mysqli_fetch_assoc($resultadoPromedio);
    $duracionPromedio = $filaPromedio['duracion_promedio'];

    // Obtener la prioridad y la fecha de creación de la appointment actual
    // Asegúrate de que esta consulta esté correctamente formada y se una con las tablas necesarias
    $queryAppointmentActual = "SELECT urgency.priority AS urgency_priority, appointments.create_time 
                        FROM appointments 
                        JOIN urgency ON appointments.Urgency_category_id = urgency.urgency_id
                        WHERE appointments.appointments_id = $appointmentId";
    $resultadoAppointmentActual = mysqli_query($connection, $queryAppointmentActual);
    $filaAppointmentActual = mysqli_fetch_assoc($resultadoAppointmentActual);
    $prioridadActual = $filaAppointmentActual['urgency_priority'];

    // Contar cuántas appointments hay antes de esta appointment
    $queryAppointmentsAntes = "SELECT COUNT(*) AS appointments_antes 
                        FROM appointments 
                        JOIN urgency ON appointments.Urgency_category_id = urgency.urgency_id
                        WHERE (urgency.priority < $prioridadActual 
                               OR (urgency.priority = $prioridadActual AND appointments.create_time < '$filaAppointmentActual[create_time]'))
                              AND appointments.status = 'pending'";
    $resultadoAppointmentsAntes = mysqli_query($connection, $queryAppointmentsAntes);
    $filaAppointmentsAntes = mysqli_fetch_assoc($resultadoAppointmentsAntes);
    $appointmentsAntes = $filaAppointmentsAntes['appointments_antes'];

    // Calcular el tiempo estimado en minutos
    $tiempoEstimadoMinutos = $appointmentsAntes * $duracionPromedio;
    // Redondeo con dos decimales
    $tiempoEstimadoMinutos = round($tiempoEstimadoMinutos, 2);
    return $tiempoEstimadoMinutos;
}


$user = $_SESSION['user']; 

// Consulta para obtener la appointment del patient
$query = "SELECT appointments.*, patients.username AS patients_username, TIMESTAMPDIFF(MINUTE, NOW(), appointments.estimated_time) AS tiempo_restante
          FROM appointments 
          JOIN patients ON appointments.Patients_patients_id = patients.patients_id
          WHERE patients.username = '$user' AND appointments.status != 'completed'
          ORDER BY appointments.create_time DESC
          LIMIT 1";
$result = $connection->query($query);
$appointment = mysqli_fetch_assoc($result);

if ($appointment) {
    $tiempoEstimado = calculateEstimatedTime($appointment['appointments_id'], $connection);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Paciente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<div class="appbar">
        <div class="appbar-left">
            Bienvenido, <?php echo htmlspecialchars($user); ?>
        </div>
        <div class="appbar-right">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
<div id="callingNotice" class="notification">
</div>
    <?php if ($appointment): ?>
        <div class="tarjeta-appointment">
            <h2>Cita programada</h2>
            <p><strong>Paciente:</strong> <?php echo htmlspecialchars($appointment['patients_username']); ?></p>
            <p><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($appointment['create_time']); ?></p>
            <p><strong>Tiempo Estimado de Espera:</strong> <?php echo $tiempoEstimado; ?> minutos</p>
        </div>
    <?php else: ?>
        <p>No tienes citas programadas.</p>
    <?php endif; ?>
    <script>
    function actualizarTiempoEstimado() {
        var appointmentId = <?php echo json_encode($appointment['appointments_id']); ?>; 

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'calcular_tiempo.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (this.status == 200) {
                var tiempoEstimado = this.responseText;
                document.getElementById('tiempo-estimado').innerText = tiempoEstimado + ' minutos';
            }
        };
        xhr.send('appointmentId=' + appointmentId);
    }

    setInterval(actualizarTiempoEstimado, 60000);

    actualizarTiempoEstimado();

    function verificarSiSeLlamaAlPaciente() {
        var appointmentId = <?php echo json_encode($appointment['appointments_id']); ?>;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_calling_patient.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                var estaSiendoLlamado = this.responseText;
                if (estaSiendoLlamado == '1') {
                    var notice = document.getElementById('callingNotice');
                    notice.innerHTML = '<p>Por favor, pase a consulta.</p>';
                    notice.style.display = 'block'; // Mostrar el aviso
                } else {
                    document.getElementById('callingNotice').style.display = 'none'; // Ocultar el aviso si no se está llamando al paciente
                }
            }
        };
        xhr.send('appointmentId=' + appointmentId);
    }

    setInterval(verificarSiSeLlamaAlPaciente, 60000);
    verificarSiSeLlamaAlPaciente(); 
</script>
</body>
</html>
