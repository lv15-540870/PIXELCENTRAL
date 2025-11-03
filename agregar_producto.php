<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipoProducto'];
    $cantidad = $_POST['cantidadDisponible'];
    $plataforma = $_POST['plataforma'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];

    $stmt = $conn->prepare("INSERT INTO juegos (nombre, precio, tipoProducto, cantidadDisponible, plataforma, descripcion, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $precio, $tipo, $cantidad, $plataforma, $descripcion, $imagen]);

    header('Location: inventario.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto - Admin</title>
    <link rel="stylesheet" href="estilos_inventario.css">
</head>
<body>
    <h1>Agregar Producto</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" required>
        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" required>
        <label>Tipo:</label>
        <select name="tipoProducto" required>
            <option value="novedad">Novedad</option>
            <option value="preventa">Preventa</option>
            <option value="catalogo">Catálogo</option>
        </select>
        <label>Cantidad Disponible:</label>
        <input type="number" name="cantidadDisponible" required>
        <label>Plataforma:</label>
        <input type="text" name="plataforma" required>
        <label>Descripción:</label>
        <textarea name="descripcion" required></textarea>
        <label>Imagen (nombre o URL):</label>
        <input type="text" name="imagen" required>
        <button type="submit">Agregar</button>
    </form>
    <a href="inventario.php" class="volver">Volver</a>
</body>
</html>
