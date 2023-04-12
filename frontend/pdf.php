<?php
require('../fpdf/fpdf.php');

class PDF extends FPDF
{

function BasicTable($header, $trabajos)
{
	$this->Cell(18,19,'');
	foreach($header as $col)
		$this->Cell(40,7,$col,1,0,'C');
	$this->Ln();
	$h = 22;
	$ha = 19;
	for ($p = 0; $p < count($trabajos); $p++) {
		$posicion = json_decode(file_get_contents("http://localhost/API/posiciones/" . $trabajos[$p]['id_posicion']), true);
        $articulo = json_decode(file_get_contents("http://localhost/API/articulos/" . $trabajos[$p]['id_articulo']), true);
        $tipo_trabajo = json_decode(file_get_contents("http://localhost/API/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
        $pedido = json_decode(file_get_contents("http://localhost/API/pedidos/" . $trabajos[$p]['id_pedido']), true);
		$tipo_articulo = json_decode(file_get_contents("http://localhost/API/tipo_articulos/" . $trabajos[$p]['id_tipo_articulo']), true);
        $logo = json_decode(file_get_contents("http://localhost/API/logos/" . $trabajos[$p]['id_logo']), true);

		$this->Cell(18,19,'');
		$this->Cell(40,19,$posicion['descripcion'],1,0,'C');
		$this->Cell(40,19,$articulo['descripcion'],1,0,'C');
		$this->Cell(40,19,$tipo_trabajo['nombre'],1,0,'C');
		$this->Cell(40,19,$pedido['fecha_pedido'],1,0,'C');

		$this->Cell(40,19,'',1,0,'C');
		$this->Image('.'.$tipo_articulo['img'] ,203,$ha,10);

		$this->Cell(40,19,'',1,0,'C');
		$this->Image('.'.$logo['img'] ,242,$h,10);
		$h +=19;
		$ha +=19;
		$this->Ln();
	}

	
}
}

$pdf = new PDF('L','mm','A4');
// T�tulos de las columnas
$header = array('Posicion', 'Articulo', 'TipoTrabajo', 'Pedido', 'TipoArticulo', 'Logo');
// Carga de datos
$trabajos = json_decode(file_get_contents("http://localhost/API/trabajos"), true);
$pdf->SetFont('Times','',11);
$pdf->AddPage();
$pdf->BasicTable($header,$trabajos);
$pdf->Output();
?>
