<?php
session_start();

if (!isset($_SESSION['carrito'])) exit;

$id = $_POST['id'] ?? null;
if (!$id || !isset($_SESSION['carrito'][$id])) {
    header("Location: carrito.php");
    exit;
}

if (isset($_POST['mas'])) {
    $_SESSION['carrito'][$id]['cantidad']++;
}

if (isset($_POST['menos'])) {
    $_SESSION['carrito'][$id]['cantidad']--;
    if ($_SESSION['carrito'][$id]['cantidad'] <= 0) {
        unset($_SESSION['carrito'][$id]);
    }
}

if (isset($_POST['eliminar'])) {
    unset($_SESSION['carrito'][$id]);
}

header("Location: carrito.php");
exit;
?>
