<?php 
session_start();
require 'conexion.php';
require __DIR__ . '/vendor/autoload.php'; // Dompdf
use Dompdf\Dompdf;

if(empty($_SESSION['carrito'])){
    header("Location: carrito.php");
    exit;
}

// Tomar orderID de GET
$orderID = $_GET['orderID'] ?? '';

// DATOS DEL COMPRADOR
if(isset($_SESSION['usuario'])){
    // Usuario logueado
    $nombre = $_SESSION['usuario']['nombre'];
    $correo = $_SESSION['usuario']['correo']; // asegúrate que coincide con tu sesión
} else {
    // Usuario invitado
    $nombre = $_POST['nombre'] ?? 'Invitado';
    $correo = $_POST['correo'] ?? 'no@correo.com';
}

// Calcular total
$total = 0;
foreach($_SESSION['carrito'] as $item){
    $total += $item['precio']*$item['cantidad'];
}

// Guardar pedido
$usuario_id = $_SESSION['usuario']['id'] ?? null;
$stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, nombre_cliente, correo_cliente, total, orderID_paypal) VALUES (?,?,?,?,?)");
$stmt->execute([$usuario_id, $nombre, $correo, $total, $orderID]);
$pedido_id = $conn->lastInsertId();

// Guardar detalle pedido
$stmt_det = $conn->prepare("INSERT INTO detalle_pedidos (pedido_id,juego_id,cantidad,precio) VALUES (?,?,?,?)");
foreach($_SESSION['carrito'] as $id => $item){
    $stmt_det->execute([$pedido_id,$id,$item['cantidad'],$item['precio']]);
}

// Generar PDF con Dompdf
$pdf = new Dompdf();
$html = "<h1>Recibo de Compra - Pixel Central</h1>";
$html .= "<p>Gracias por su compra, <strong>$nombre</strong></p>";
$html .= "<p>Correo: <strong>$correo</strong></p>";
$html .= "<table border='1' cellpadding='5' cellspacing='0'><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>";

foreach($_SESSION['carrito'] as $item){
    $subtotal = $item['precio']*$item['cantidad'];
    $html .= "<tr>
                <td>{$item['nombre']}</td>
                <td>{$item['cantidad']}</td>
                <td>$".number_format($item['precio'],2)."</td>
                <td>$".number_format($subtotal,2)."</td>
              </tr>";
}
$html .= "</table>";
$html .= "<p><strong>Total: $".number_format($total,2)."</strong></p>";

$pdf->loadHtml($html);
$pdf->setPaper('A4','portrait');
$pdf->render();

if(!file_exists('recibos')) mkdir('recibos',0777,true);
$pdf_file = "recibos/recibo_".time().".pdf";
file_put_contents($pdf_file, $pdf->output());

// Guardar PDF en sesión para index.php
$_SESSION['pdf_file'] = $pdf_file;

// Vaciar carrito
$_SESSION['carrito'] = [];

// REDIRECCIONAR a index
header("Location: index.php");
exit;
