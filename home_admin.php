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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body onload="openTab(null, 'urgency')">
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
    <button class="tab-button" onclick="openTab(event, 'urgency')">Urgency</button>
    <button class="tab-button" onclick="openTab(event, 'job')">Job</button>
    <button class="tab-button" onclick="openTab(event, 'staff')">Staff</button>
</div>

<!-- Contenido de las Pestañas -->
<div id="urgency" class="tabcontent">
    <!-- Contenido de Urgency -->
</div>
<div id="job" class="tabcontent">
    <!-- Contenido de Job -->
</div>
<div id="staff" class="tabcontent">
    <!-- Contenido de Staff -->
</div>
<a href="#" id="floatingButton" class="floating-button">
    <i class="fas fa-plus"></i>
</a>
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
            var floatingButton = document.getElementById('floatingButton');
            switch(tabName) {
                case 'staff':
                    floatingButton.href = 'create_staff.php';
                    floatingButton.style.display = 'flex';
                    break;
                default:
                    floatingButton.style.display = 'none';
            }
        }
        // Añade el event listener para DOMContentLoaded
        document.addEventListener("DOMContentLoaded", function() {
            openTab(null, 'urgency');
        });
    </script>
</body>
</html>
