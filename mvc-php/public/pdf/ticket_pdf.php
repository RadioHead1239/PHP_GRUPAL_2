<?php
// public/pdf/ticket_pdf.php

require_once '../../app/dao/VentaDAO.php';
// Incluye FPDF
require_once __DIR__ . '/fpdf/fpdf.php';

// Recibe el id de la venta por GET
$idVenta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($idVenta <= 0) {
    die('ID de venta inválido');
}

$ventaDAO = new VentaDAO();
$venta = $ventaDAO->leerVentaPorId($idVenta);
if (!$venta) {
    die('Venta no encontrada');
}
$detalles = $ventaDAO->leerDetalleVenta($idVenta);

// --- Generación del PDF ---
$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetFont('helvetica', 'B', 9);
// CABECERA PERSONALIZADA
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(59, 6, mb_convert_encoding('IMPORTADORA INDUSTRIAS S.A.C.', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('helvetica', '', 9);
$pdf->Cell(70, 5, 'RUC: 12345678901', 0, 1, 'C');
$pdf->Cell(70, 5, mb_convert_encoding('Calle Falsa 123, Ciudad XYZ', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(70, 5, 'Tel: (01) 123-4567', 0, 1, 'C');
$pdf->Ln(1);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(70, 6, mb_convert_encoding('COMPROBANTE DE PAGO', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(70, 5, mb_convert_encoding('No válido como factura SUNAT', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Ln(1);
// Logo (opcional, ignora error si no se puede mostrar)
$logoPath = __DIR__ . '/../assets/img/logo.jpg';
if (file_exists($logoPath)) {
    try {
        $pdf->Image($logoPath, 15, 2, 45);
    } catch (Exception $e) {
        // No mostrar logo si hay error
    }
}
$pdf->Ln(7);
$pdf->MultiCell(70, 5, '', 0, 'C');
$pdf->Ln(1);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(30, 5, mb_convert_encoding('Número Ticket:', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(40, 5, $venta['IdVenta'], 0, 1, 'L');
$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');
// Tabla productos
$pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
$pdf->Cell(30, 4, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(15, 4, 'P. Unit', 0, 0, 'C');
$pdf->Cell(15, 4, 'Importe', 0, 1, 'C');
$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');
$totalProductos = 0;
$pdf->SetFont('helvetica', '', 7);
foreach ($detalles as $detalle) {
    $importe = number_format($detalle['Cantidad'] * $detalle['PrecioUnitario'], 2, '.', ',');
    $totalProductos += $detalle['Cantidad'];
    $pdf->Cell(10, 4, $detalle['Cantidad'], 0, 0, 'L');
    $yInicio = $pdf->GetY();
    $pdf->MultiCell(30, 4, mb_convert_encoding($detalle['ProductoNombre'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
    $yFin = $pdf->GetY();
    $pdf->SetXY(45, $yInicio);
    $pdf->Cell(15, 4, 'S/. ' . number_format($detalle['PrecioUnitario'], 2, '.', ','), 0, 0, 'C');
    $pdf->SetXY(60, $yInicio);
    $pdf->Cell(15, 4, 'S/. ' . $importe, 0, 1, 'R');
    $pdf->SetY($yFin);
}
$pdf->Ln();
$pdf->Cell(70, 4, mb_convert_encoding('Número de artículos:  ' . $totalProductos, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(70, 5, sprintf('Total: S/. %s', number_format($venta['Total'], 2, '.', ',')), 0, 1, 'R');
$pdf->Ln(2);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(35, 5, mb_convert_encoding('Fecha: ', 'ISO-8859-1', 'UTF-8') . $venta['FechaVenta'], 0, 0, 'C');
$pdf->Cell(35, 5, mb_convert_encoding('Vendedor: ', 'ISO-8859-1', 'UTF-8') . $venta['VendedorNombre'], 0, 1, 'C');
$pdf->Ln();
$pdf->MultiCell(70, 5, mb_convert_encoding('AGRADECEMOS SU PREFERENCIA, ¡VUELVA PRONTO!', 'ISO-8859-1', 'UTF-8'), 0, 'C');
$pdf->Ln(2);
$pdf->SetFont('helvetica', '', 7);
$pdf->MultiCell(70, 4, "Este comprobante NO es un comprobante electrónico autorizado por SUNAT.\nNo tiene validez tributaria ni puede usarse para crédito fiscal, gasto o costo tributario.\nEmitido solo como constancia interna de compra/venta.\nNo genera derecho a devolución de IGV ni a deducción de gasto.", 0, 'C');
$pdf->Ln(2);
// QR de ejemplo (requiere PHP QR code o una imagen QR generada previamente)
$qrPath = __DIR__ . '/../assets/img/qr_ejemplo.png';
if (file_exists($qrPath)) {
    try {
        $pdf->Image($qrPath, 25, $pdf->GetY(), 30, 30);
    } catch (Exception $e) {}
}
$pdf->Ln(32);
$pdf->SetFont('helvetica', 'I', 7);
$pdf->Cell(70, 4, 'Gracias por su compra', 0, 1, 'C');
$filename = 'ticket_venta_' . $venta['IdVenta'] . '.pdf';
$pdf->Output('D', $filename);
exit;
