document.addEventListener('DOMContentLoaded', function() {
    var loginContainer = document.querySelector('.login-container');
    loginContainer.addEventListener('dblclick', function(e) {
        if (e.target === loginContainer) {
            showAdminLogin();
        }
    });
});

function showAdminLogin() {
    var adminFormHtml = `
        <div class="form-container" id="admin-login-form" style="display:none;">
            <div class="input-group">
                <label for="admin-username">Usuario Administrador:</label>
                <input type="text" id="admin-username" name="admin-username">
            </div>
            <div class="input-group">
                <label for="admin-password">Contraseña Administrador:</label>
                <input type="password" id="admin-password" name="admin-password">
            </div>
            <button onclick="adminLogin()" class="submit-button">Iniciar Sesión como Admin</button>
        </div>
    `;
    document.body.innerHTML += adminFormHtml;
    document.getElementById('admin-login-form').style.display = 'block';
}

function adminLogin() {
    // Implementa la lógica de inicio de sesión para el administrador aquí
    console.log('Intento de inicio de sesión como administrador');
}
