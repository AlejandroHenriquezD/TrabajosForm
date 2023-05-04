<?php
require('../fpdf.php');


$id_pedido = 1;
$pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"), true);

class PDF extends FPDF
{

	function TablaArticulo($header, $trabajo, $id_pedido, $y)
	{
		$this->Cell(5, 12, '');
		foreach ($header as $col)
			$this->Cell(40, 7, $col, 'B', 0, 'L');
		$this->Ln();
		$h = 26;
		$ha = 22;

		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajo['id_tipo_articulo']), true);

		$this->Cell(5, 12, '');

		$this->Cell(40, 19, '', 0, 0, 'C');
		$this->Image('../.' . $tipo_articulo['img'], 22, $y, 20);

		$this->Cell(5, 12, '');

		$h += 19;
		$ha += 19;
	}

	function TablaTrabajo($header, $trabajos, $id_pedido)
	{

		$this->Cell(5, 7, '');
		foreach ($header as $col)
			$this->Cell(40, 7, $col, 'B', 0, 'C');
		$this->Ln();
		$h = 26;
		$ha = 22;
		$tipo_trabajosFiltrados = array(); foreach ($trabajos as $trabajo) {
			$tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajo['id_tipo_trabajo']), true);
			// $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajo['id_logo']), true);

			if (!in_array($tipo_trabajo, $tipo_trabajosFiltrados)) {
				array_push($tipo_trabajosFiltrados, $tipo_trabajo);
			}

		}
		foreach ($tipo_trabajosFiltrados as $tipo_trabajo) {

			$this->Cell(15, 12, '');

			$this->Cell(40, 14, '', 0, 0, 'C');


			$posicionesFiltradas = array();
			foreach ($trabajos as $trabajo) {
				if ($tipo_trabajo['id'] == $trabajo['id_tipo_trabajo']) {
					$posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajo['id_posicion']), true);
					array_push($posicionesFiltradas, $posicion);

				}
			}
			$this->Cell(40, 14 * count($posicionesFiltradas), $tipo_trabajo['nombre'], 1, 0, 'C');
			foreach ($posicionesFiltradas as $posicion) {
				$this->SetX(105);
				$this->Cell(40, 14, $posicion['descripcion'], 1, 0, 'C');
				$this->Cell(40, 14, '', 0, 0, 'C');
				$h += 14;
				$ha += 14;
				$this->Ln();
			}

		}
	}

}

$pdf = new PDF('P', 'mm', 'A4');
// T�tulos de las columnas

// Carga de datos
$trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
$pdf->SetFont('Times', '', 9);
$pdf->AddPage();
$pdf->Cell(0, 1, 'Numero Pedido: ' . $id_pedido . '                                Fecha Pedido: ' . $pedidos[$id_pedido]["fecha_pedido"], 0, 1, 'C');
$pdf->Ln();
$pdf->Ln();
$articulos = json_decode(file_get_contents("http://localhost/trabajosform/articulos/"), true);
$y = 22;
foreach ($articulos as $articulo) {
	$header = array($articulo['descripcion']);
	$trabajosFiltrados = array();
	foreach ($trabajos as $trabajo) {
		if ($trabajo['id_articulo'] == $articulo['id']) {
			array_push($trabajosFiltrados, $trabajo);
		}
	}
	echo json_encode($trabajosFiltrados);
	$pdf->TablaArticulo($header, $trabajosFiltrados[0], $id_pedido, $y);
	$header = array('Tipos de trabajo', 'Posiciones');
	$pdf->TablaTrabajo($header, $trabajosFiltrados, $id_pedido);
	$y += 14 * count($trabajosFiltrados) + 14;
}

$pdf->Output();