<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Buscar usuario por correo
    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validar usuario y contraseña
    if ($usuario && $password === $usuario['password']) {
        // Guardar toda la información en un solo array de sesión
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
            'rol' => $usuario['rol']
        ];

        // Redirigir según el rol
        if ($usuario['rol'] === 'admin') {
            header("Location: index.php");
        } else {
            header("Location: index.php?login=success");
        }
        exit;
    } else {
        echo "<script>
            alert('Correo o contraseña incorrectos');
            window.location.href='login.php';
        </script>";
    }
}
?>
