<?php
require('../fpdf.php');

$pdf = new FPDF('P','mm','A3');
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'�Hola, Mundo!');
$pdf->Output();
?>
