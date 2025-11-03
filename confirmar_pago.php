<?php
session_start();
require 'conexion.php';

if(empty($_SESSION['carrito'])) {
    exit("Carrito vacío.");
}

// Usuario si hay sesión
$usuario_id = $_SESSION['usuario_id'] ?? null;

// Procesar cada producto en carrito
foreach($_SESSION['carrito'] as $id => $cantidad) {
    // Actualizar inventario
    $stmt = $conn->prepare("UPDATE juegos SET cantidadDisponible = cantidadDisponible - ? WHERE id = ?");
    $stmt->execute([$cantidad, $id]);

    // Registrar compra
    $stmt2 = $conn->prepare("INSERT INTO compras (usuario_id, juego_id, cantidad, fecha) VALUES (?, ?, ?, NOW())");
    $stmt2->execute([$usuario_id, $id, $cantidad]);
}

// Limpiar carrito
$_SESSION['carrito'] = [];

// Aquí podrías generar un PDF con FPDF/TCPDF usando los datos
echo "Compra realizada correctamente. <a href='index.php'>Volver a la tienda</a>";
?>
