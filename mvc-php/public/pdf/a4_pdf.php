<?php
require_once '../../app/dao/VentaDAO.php';
require_once 'fpdf/fpdf.php';

// Recibe el id de la venta por GET o POST
$idVenta = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);
if (!$idVenta) {
    die('ID de venta no especificado');
}

$ventaDAO = new VentaDAO();
$venta = $ventaDAO->leerVentaPorId($idVenta);
$detalles = $ventaDAO->leerDetalleVenta($idVenta);

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, mb_convert_encoding('Factura de Venta', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, mb_convert_encoding('MI EMPRESA S.A.C. - RUC: 12345678901', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(0, 7, mb_convert_encoding('Calle Falsa 123, Ciudad XYZ', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(5);

// Datos de la venta
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(40, 7, mb_convert_encoding('Número de Ticket:', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(40, 7, $venta['IdVenta'], 0, 1);
$pdf->Cell(40, 7, mb_convert_encoding('Fecha:', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(40, 7, $venta['FechaVenta'], 0, 1);
$pdf->Cell(40, 7, mb_convert_encoding('Vendedor:', 'ISO-8859-1', 'UTF-8'), 0, 0);
$pdf->Cell(40, 7, mb_convert_encoding($venta['VendedorNombre'], 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->Ln(5);

// Tabla de productos
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 7, 'Cant.', 1, 0, 'C');
$pdf->Cell(90, 7, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 1, 0, 'C');
$pdf->Cell(30, 7, 'P. Unit (S/.)', 1, 0, 'C');
$pdf->Cell(30, 7, 'Importe (S/.)', 1, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
foreach ($detalles as $detalle) {
    $importe = number_format($detalle['Cantidad'] * $detalle['PrecioUnitario'], 2, '.', ',');
    $pdf->Cell(20, 7, $detalle['Cantidad'], 1, 0, 'C');
    $pdf->Cell(90, 7, mb_convert_encoding($detalle['ProductoNombre'], 'ISO-8859-1', 'UTF-8'), 1, 0, 'L');
    $pdf->Cell(30, 7, number_format($detalle['PrecioUnitario'], 2, '.', ','), 1, 0, 'R');
    $pdf->Cell(30, 7, $importe, 1, 1, 'R');
}
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(140, 10, 'TOTAL', 1, 0, 'R');
$pdf->Cell(30, 10, 'S/. ' . number_format($venta['Total'], 2, '.', ','), 1, 1, 'R');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(0, 7, mb_convert_encoding('Este comprobante no es válido como factura SUNAT. Solo para control interno.', 'ISO-8859-1', 'UTF-8'), 0, 'C');

$pdf->Output('I', 'venta_A4.pdf');
