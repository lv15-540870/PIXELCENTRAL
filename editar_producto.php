<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM juegos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $tipo = $_POST['tipoProducto'];
    $cantidad = $_POST['cantidadDisponible'];
    $plataforma = $_POST['plataforma'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];

    $stmt = $conn->prepare("UPDATE juegos SET nombre=?, precio=?, tipoProducto=?, cantidadDisponible=?, plataforma=?, descripcion=?, imagen=? WHERE id=?");
    $stmt->execute([$nombre, $precio, $tipo, $cantidad, $plataforma, $descripcion, $imagen, $id]);

    header('Location: inventario.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto - Admin</title>
    <link rel="stylesheet" href="estilos_inventario.css">
</head>
<body>
    <h1>Editar Producto</h1>
    <form method="POST">
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= $producto['nombre'] ?>" required>
        <label>Precio:</label>
        <input type="number" step="0.01" name="precio" value="<?= $producto['precio'] ?>" required>
        <label>Tipo:</label>
        <select name="tipoProducto" required>
            <option value="novedad" <?= $producto['tipoProducto']=='novedad'?'selected':'' ?>>Novedad</option>
            <option value="preventa" <?= $producto['tipoProducto']=='preventa'?'selected':'' ?>>Preventa</option>
            <option value="catalogo" <?= $producto['tipoProducto']=='catalogo'?'selected':'' ?>>Catálogo</option>
        </select>
        <label>Cantidad Disponible:</label>
        <input type="number" name="cantidadDisponible" value="<?= $producto['cantidadDisponible'] ?>" required>
        <label>Plataforma:</label>
        <input type="text" name="plataforma" value="<?= $producto['plataforma'] ?>" required>
        <label>Descripción:</label>
        <textarea name="descripcion" required><?= $producto['descripcion'] ?></textarea>
        <label>Imagen (nombre o URL):</label>
        <input type="text" name="imagen" value="<?= $producto['imagen'] ?>" required>
        <button type="submit">Actualizar</button>
    </form>
    <a href="inventario.php" class="volver">Volver</a>
</body>
</html>
