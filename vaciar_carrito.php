<?php
session_start();
unset($_SESSION['carrito']); // vacía el carrito
header('Location: carrito.php');
exit;
