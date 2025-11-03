<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':correo', $correo);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>
            alert('El correo ya está registrado. Intenta iniciar sesión.');
            window.location.href='login.php';
        </script>";
        exit;
    }

    // Encriptar contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, correo, password) VALUES (:nombre, :correo, :password)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':correo', $correo);
    $stmt->bindParam(':password', $passwordHash);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registro exitoso. ¡Ya puedes iniciar sesión!');
            window.location.href='login.php';
        </script>";
    } else {
        echo "<script>
            alert('Error al registrar. Intenta de nuevo.');
            window.location.href='login.php';
        </script>";
    }
}
?>
