<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Registro - Pixel Central</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">

<div class="login-container">
    <h2 id="titulo-form">Iniciar Sesión</h2>

    <form id="form-login" method="POST" action="procesar_login.php">
        <label for="correo">Correo Electrónico</label>
        <input type="email" name="correo" id="correo" placeholder="Ejemplo: usuario@gmail.com" required>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" placeholder="Ingresa tu contraseña" required>

        <button type="submit" name="login">Ingresar</button>
    </form>

    <form id="form-registro" method="POST" action="procesar_registro.php" style="display: none;">
        <label for="nombre">Nombre completo</label>
        <input type="text" name="nombre" id="nombre" placeholder="Aqui tu nombre completo " required>

        <label for="fecha_nacimiento">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

        <label for="correo_reg">Correo Electrónico</label>
        <input type="email" name="correo" id="correo_reg" placeholder="Tu correo electronico, ejemplo: usuario@gmail.com" required>

        <label for="password_reg">Contraseña</label>
        <input type="password" name="password" id="password_reg" placeholder="Crea una contraseña segura" required>

        <button type="submit" name="registrar">Registrarse</button>
    </form>

    <span id="toggle-text" class="toggle-link" onclick="toggleForm()">¿No tienes cuenta? Regístrate aquí</span>
</div>

<script>
function toggleForm() {
    const formLogin = document.getElementById('form-login');
    const formRegistro = document.getElementById('form-registro');
    const toggleText = document.getElementById('toggle-text');
    const titulo = document.getElementById('titulo-form');

    if (formLogin.style.display === 'none') {
        formLogin.style.display = 'block';
        formRegistro.style.display = 'none';
        toggleText.textContent = "¿No tienes cuenta? Regístrate aquí";
        titulo.textContent = "Iniciar Sesión";
    } else {
        formLogin.style.display = 'none';
        formRegistro.style.display = 'block';
        toggleText.textContent = "¿Ya tienes cuenta? Inicia sesión aquí";
        titulo.textContent = "Crear Cuenta";
    }
}
</script>

</body>
</html>
