<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Añadir Patient</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Añadir Nuevo Paciente</h2>
        <form id="addPatientForm" action="create_patient.php" method="post">
            <div class="input-group">
                <label for="username">Nombre de Usuario:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="passwordUser" required>
            </div>
            <div class="input-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit" class="submit-button">Añadir paciente</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('addPatientForm');
            form.onsubmit = function (e) {
                e.preventDefault();

                var formData = new FormData(form);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'call_create_patient.php', true);

                xhr.onload = function () {
                    if (xhr.responseText === 'success') {
                        alert('Usuario creado correctamente');
                        window.location.href = 'index.html';
                    } else {
                        alert('Hubo un error al crear el usuario');
                    }
                };

                xhr.send(formData);
            };
        });
    </script>
</body>
</html>
