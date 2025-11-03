<?php
session_start();
require 'conexion.php';
require __DIR__ . '/vendor/setasign/fpdf/fpdf.php';
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
if(!$input || empty($input['orderID'])){
    echo json_encode(['ok'=>false,'msg'=>'No se recibió orderID']);
    exit;
}

$orderID = $input['orderID'];
$nombre = !empty($input['nombre']) ? $input['nombre'] : ($_SESSION['nombre'] ?? 'Invitado');
$correo = !empty($input['correo']) ? $input['correo'] : ($_SESSION['correo'] ?? 'no@correo.com');

try {
    // Generar PDF
    if(!file_exists('recibos')) mkdir('recibos',0777,true);

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(0,10,"Recibo de Compra - Pixel Central",0,1,'C');
    $pdf->Ln(5);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,8,"Nombre: $nombre",0,1);
    $pdf->Cell(0,8,"Correo: $correo",0,1);
    $pdf->Ln(5);

    $pdf->Cell(80,8,"Producto",1);
    $pdf->Cell(30,8,"Cantidad",1);
    $pdf->Cell(40,8,"Precio",1);
    $pdf->Cell(40,8,"Subtotal",1);
    $pdf->Ln();

    $total = 0;
    foreach($_SESSION['carrito'] as $prod){
        $subtotal = $prod['precio'] * $prod['cantidad'];
        $total += $subtotal;

        // Disminuir stock
        $stmt = $conn->prepare("UPDATE juegos SET cantidadDisponible = cantidadDisponible - ? WHERE id=?");
        $stmt->execute([$prod['cantidad'],$prod['id']]);

        $pdf->Cell(80,8,$prod['nombre'],1);
        $pdf->Cell(30,8,$prod['cantidad'],1);
        $pdf->Cell(40,8,'$'.number_format($prod['precio'],2),1);
        $pdf->Cell(40,8,'$'.number_format($subtotal,2),1);
        $pdf->Ln();
    }

    $pdf->Ln(5);
    $pdf->Cell(0,8,"TOTAL: $" . number_format($total,2),0,1,'R');

    $pdf_file = "recibos/recibo_".time().".pdf";
    $pdf->Output('F',$pdf_file);

    // Vaciar carrito y marcar compra confirmada
    $_SESSION['carrito'] = [];
    $_SESSION['compra_confirmada'] = true;
    $_SESSION['pdf_file'] = $pdf_file;

    echo json_encode(['ok'=>true,'mensaje'=>'Compra realizada exitosamente','pdf_file'=>$pdf_file]);
} catch(Exception $e){
    echo json_encode(['ok'=>false,'msg'=>$e->getMessage()]);
}
