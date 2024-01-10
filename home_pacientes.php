<?php
session_start();
include 'db_connect.php';

// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}
function calcularTiempoEstimado($citaId, $conexion) {
    // Calcular la duración media de las citas completadas en minutos
    $queryPromedio = "SELECT AVG(TIMESTAMPDIFF(MINUTE, create_time, finish_time)) AS duracion_promedio 
                      FROM citas 
                      WHERE status = 'completed'";
    $resultadoPromedio = mysqli_query($conexion, $queryPromedio);
    $filaPromedio = mysqli_fetch_assoc($resultadoPromedio);
    $duracionPromedio = $filaPromedio['duracion_promedio'];

    // Obtener la prioridad y la fecha de creación de la cita actual
    // Asegúrate de que esta consulta esté correctamente formada y se una con las tablas necesarias
    $queryCitaActual = "SELECT urgencia.priority AS urgencia_priority, citas.create_time 
                        FROM citas 
                        JOIN urgencia ON citas.Urgencia_category_id = urgencia.urgencia_id
                        WHERE citas.citas_id = $citaId";
    $resultadoCitaActual = mysqli_query($conexion, $queryCitaActual);
    $filaCitaActual = mysqli_fetch_assoc($resultadoCitaActual);
    $prioridadActual = $filaCitaActual['urgencia_priority'];

    // Contar cuántas citas hay antes de esta cita
    $queryCitasAntes = "SELECT COUNT(*) AS citas_antes 
                        FROM citas 
                        JOIN urgencia ON citas.Urgencia_category_id = urgencia.urgencia_id
                        WHERE (urgencia.priority < $prioridadActual 
                               OR (urgencia.priority = $prioridadActual AND citas.create_time < '$filaCitaActual[create_time]'))
                              AND citas.status = 'pending'";
    $resultadoCitasAntes = mysqli_query($conexion, $queryCitasAntes);
    $filaCitasAntes = mysqli_fetch_assoc($resultadoCitasAntes);
    $citasAntes = $filaCitasAntes['citas_antes'];

    // Calcular el tiempo estimado en minutos
    $tiempoEstimadoMinutos = $citasAntes * $duracionPromedio;
    return $tiempoEstimadoMinutos;
}


$user = $_SESSION['user']; 

// Consulta para obtener la cita del paciente
$query = "SELECT citas.*, pacientes.username AS pacientes_username, TIMESTAMPDIFF(MINUTE, NOW(), citas.estimated_time) AS tiempo_restante
          FROM citas 
          JOIN pacientes ON citas.Pacientes_pacientes_id = pacientes.pacientes_id
          WHERE pacientes.username = '$user' AND citas.status = 'pending'
          ORDER BY citas.create_time DESC
          LIMIT 1";
$result = $conexion->query($query);
$cita = mysqli_fetch_assoc($result);

// Calcula el tiempo estimado si la cita existe
if ($cita) {
    $tiempoEstimado = calcularTiempoEstimado($cita['citas_id'], $conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
    <style>
        /* Tus estilos existentes */
        .tarjeta-cita {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <!-- Tu estructura existente de la página -->

    <!-- Sección de la cita -->
    <?php if ($cita): ?>
        <div class="tarjeta-cita">
            <h2>Cita Programada</h2>
            <p><strong>Paciente:</strong> <?php echo htmlspecialchars($cita['pacientes_username']); ?></p>
            <p><strong>Fecha de Creación:</strong> <?php echo htmlspecialchars($cita['create_time']); ?></p>
            <p><strong>Tiempo Estimado de Espera:</strong> <?php echo $tiempoEstimado; ?> minutos</p>
        </div>
    <?php else: ?>
        <p>No tienes citas programadas.</p>
    <?php endif; ?>
    
</body>
</html>
