<?php
session_start();

$id = $_GET['id'];
$accion = $_GET['accion'];

if(isset($_SESSION['carrito'][$id])) {
    switch($accion) {
        case 'agregar':
            $_SESSION['carrito'][$id]['cantidad']++;
            break;
        case 'disminuir':
            $_SESSION['carrito'][$id]['cantidad']--;
            if($_SESSION['carrito'][$id]['cantidad'] <= 0) {
                unset($_SESSION['carrito'][$id]);
            }
            break;
        case 'eliminar':
            unset($_SESSION['carrito'][$id]);
            break;
    }
}

// Redirigir al carrito
header("Location: carrito.php");
exit;
?>
