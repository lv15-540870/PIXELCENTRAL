<?php
session_start();
require 'conexion.php';

// Validar admin
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $conn->prepare("DELETE FROM juegos WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: inventario.php');
    exit;
}

// Obtener todos los productos
$stmt = $conn->prepare("SELECT * FROM juegos");
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Admin</title>
    <link rel="stylesheet" href="estilos_inventario.css">
</head>
<body>
    <body>
    <div class="top-bar">
        <a href="index.php" class="btn volver"> Volver al Menú Principal</a>
        <a href="cerrar_sesion.php" class="btn salir">Cerrar Sesión</a>
    </div>

    <h1>Panel de Inventario</h1>
    <a href="agregar_producto.php" class="btn">Agregar Producto</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Plataforma</th>
            <th>Descripción</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nombre'] ?></td>
            <td>$<?= $p['precio'] ?></td>
            <td><?= $p['tipoProducto'] ?></td>
            <td><?= $p['cantidadDisponible'] ?></td>
            <td><?= $p['plataforma'] ?></td>
            <td><?= $p['descripcion'] ?></td>
            <td><?= $p['imagen'] ?></td>
            <td>
                <a href="editar_producto.php?id=<?= $p['id'] ?>" class="btn">Editar</a>
                <a href="inventario.php?eliminar=<?= $p['id'] ?>" class="btn eliminar" onclick="return confirm('¿Seguro quieres eliminar este producto?')">Eliminar</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
