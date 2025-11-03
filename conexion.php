<?php
$host = "localhost";
$port = 3306; // puerto de MySQL
$dbname = "pixelcentral";
$user = "root";
$pass = "";

// Conexión
try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*echo "Conexión exitosa"*/;
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>