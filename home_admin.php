<?php
session_start();
include 'db_connect.php';
// Comprueba si el usuario está logueado
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); 
    exit();
}

$username = $_SESSION['user']; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body onload="openTab(null, 'urgencia')">
    <div class="appbar">
        <div class="appbar-left">
            Bienvenido, <?php echo htmlspecialchars($username); ?>
        </div>
        <div class="appbar-right">
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    
    <!-- Contenedor de Pestañas -->
<div class="tabs">
    <button class="tab-button" onclick="openTab(event, 'urgencia')">Urgencias</button>
    <button class="tab-button" onclick="openTab(event, 'puesto')">Puesto</button>
    <button class="tab-button" onclick="openTab(event, 'personal')">Personal</button>
</div>

<!-- Contenido de las Pestañas -->
<div id="urgencia" class="tabcontent">
    <!-- Contenido de Urgencias -->
</div>
<div id="puesto" class="tabcontent">
    <!-- Contenido de Puesto -->
</div>
<div id="personal" class="tabcontent">
    <!-- Contenido de Personal -->
</div>
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;

            // Oculta todos los elementos con class="tabcontent"
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Elimina la clase "active" de todos los elementos con class="tablinks"
            tablinks = document.getElementsByClassName("tab-button");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            // Muestra el contenido de la pestaña actual
            document.getElementById(tabName).style.display = "block";
            
            if (evt) {
                evt.currentTarget.classList.add("active");
            } else {
                // Para la carga inicial, encuentra y activa el botón correspondiente
                var activeTab = document.querySelector(`.tab-button[onclick="openTab(event, '${tabName}')"]`);
                if (activeTab) {
                    activeTab.classList.add("active");
                }
            }

            // Llamada AJAX para cargar el contenido de la pestaña
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById(tabName).innerHTML = this.responseText;
                }
            };
            xhr.open("GET", tabName + ".php", true);
            xhr.send();
        }

        // Añade el event listener para DOMContentLoaded
        document.addEventListener("DOMContentLoaded", function() {
            openTab(null, 'urgencia');
        });
    </script>
</body>
</html>
