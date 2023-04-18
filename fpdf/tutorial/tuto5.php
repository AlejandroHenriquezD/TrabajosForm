<?php
require('../fpdf.php');


$id_pedido = 1;
$pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"),true);

class PDF extends FPDF
{

function BasicTable($header, $trabajos)
{
	$this->Cell(38,19,'');
	foreach($header as $col)
		$this->Cell(40,7,$col,1,0,'C');
	$this->Ln();
	$h = 26;
	$ha = 22;
	for ($p = 0; $p < count($trabajos); $p++) {
		$posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
        $articulo = json_decode(file_get_contents("http://localhost/trabajosform/articulos/" . $trabajos[$p]['id_articulo']), true);
        $tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajos[$p]['id_tipo_articulo']), true);
        $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);

		$this->Cell(38,19,'');
		$this->Cell(40,19,$posicion['descripcion'],1,0,'C');
		$this->Cell(40,19,$articulo['descripcion'],1,0,'C');
		$this->Cell(40,19,$tipo_trabajo['nombre'],1,0,'C');

		$this->Cell(40,19,'',1,0,'C');
		$this->Image('../.'.$tipo_articulo['img'] ,183,$ha,10);

		$this->Cell(40,19,'',1,0,'C');
		$this->Image('../.'.$logo['img'] ,222,$h,10);
		$h +=19;
		$ha +=19;
		$this->Ln();
	}

	
}
}

$pdf = new PDF('L','mm','A4');
// T�tulos de las columnas
$header = array('Posicion', 'Articulo', 'TipoTrabajo', 'TipoArticulo', 'Logo');
// Carga de datos
$trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
$pdf->SetFont('Times','',11);
$pdf->AddPage();
$pdf->Cell(0,1,'Numero Pedido: '. $id_pedido . '                                Fecha Pedido: ' . $pedidos[$id_pedido]["fecha_pedido"],0,1,'C');
$pdf->Ln();
$pdf->Ln();
$pdf->BasicTable($header,$trabajos);
$pdf->Output();
?>
