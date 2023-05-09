<?php
session_start();
require('../fpdf/fpdf.php');

$ejercicio_pedido = $_GET["ejercicio_pedido"];
$serie_pedido = $_GET["serie_pedido"];
$numero_pedido = $_GET["numero_pedido"];
$pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"), true);

class PDF extends FPDF
{

	function TablaArticulo($header, $trabajo, $x, $y)
	{
		$this->SetXY($x, $y);
		foreach ($header as $col)

			$this->Rect($x, $y, 25, 7, "");
		$this->Cell(25, 7, $col, 'B', 0, 'L');
		// $this->ClipOut();
		$this->Ln();

		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajo['id_tipo_articulo']), true);

		$this->SetXY($x, $y + 7);
		$this->Cell(25, 19, '', 0, 0, 'C');
		$this->Image('.' . $tipo_articulo['img'], 13, $y + 8, 20);
	}

	function TablaTrabajo($header, $trabajos, $x, $y)
	{

		$possum = 0;
		$this->SetXY($x, $y);
		foreach ($header as $col)
			$this->Cell(25, 7, $col, 'B', 0, 'C');
		$this->Ln();
		$tipo_trabajosFiltrados = array();

		foreach ($trabajos as $trabajo) {
			$tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajo['id_tipo_trabajo']), true);
			// $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajo['id_logo']), true);

			if (!in_array($tipo_trabajo, $tipo_trabajosFiltrados)) {
				array_push($tipo_trabajosFiltrados, $tipo_trabajo);
			}
		}
		foreach ($tipo_trabajosFiltrados as $tipo_trabajo) {

			$posicionesFiltradas = array();
			foreach ($trabajos as $trabajo) {
				if ($tipo_trabajo['id'] == $trabajo['id_tipo_trabajo']) {
					$posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajo['id_posicion']), true);
					array_push($posicionesFiltradas, $posicion);
				}
			}
			$this->SetXY($x, $y + 7 + $possum);
			$this->Cell(25, 10 * count($posicionesFiltradas), $tipo_trabajo['nombre'], 1, 0, 'C');
			foreach ($posicionesFiltradas as $posicion) {
				$this->SetX(70);
				$this->Cell(25, 10, $posicion['descripcion'], 1, 0, 'C');
				// $this->Cell(40, 14, '', 0, 0, 'C');
				$this->Ln();
				$possum += 10;
			}
		}
		$possum = 0;
	}
}

$pdf = new PDF('P', 'mm', 'A4');
// T�tulos de las columnas

// Carga de datos
$trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
$pdf->SetFont('Times', '', 8);
$pdf->AddPage();
$pdf->Cell(0, 1, 'Ejercicio Pedido: ' . $ejercicio_pedido . '                 Serie Pedido: ' . $serie_pedido . '                 Numero Pedido: ' . $numero_pedido, 0, 1, 'C');
$pdf->Ln();
$pdf->Ln();
$articulos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos.php"), true);

// echo json_encode($trabajos);
// echo json_encode($articulos);

$y = 22;

include_once "../BDReal/numTienda.php";
include_once "../BDReal/conexion_exit.php";

$tsql = "SELECT DISTINCT
						CodigoAlmacen,
						CodigoArticulo,
						SeriePedido,
						NumeroPedido,
						DescripcionArticulo
		FROM PedidoVentaLineas
		WHERE EjercicioPedido = $ejercicio_pedido
		AND SeriePedido = '$serie_pedido'
		AND NumeroPedido = $numero_pedido
		AND (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
		AND TipoArticulo = 'M'
		AND CodigoAlmacen = '06'
		";


$getResults = sqlsrv_query($conn, $tsql);

$data = [];

while ($articulo = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	$data[] = $articulo;
	$codigo_articulo = str_replace(' ', '', $articulo['CodigoArticulo']);
	$descripcion_articulo = str_replace(' ', '', $articulo['DescripcionArticulo']);
	$index = 0;
	$trabajosFiltrados = array();
	$header = array($articulo['DescripcionArticulo']);
	foreach ($trabajos as $trabajo) {
		if (
			$trabajo['ejercicio_pedido'] == $ejercicio_pedido &&
			$trabajo['serie_pedido'] == $serie_pedido &&
			$trabajo['numero_pedido'] == $numero_pedido
		) {
			if (
				$trabajo['codigo_articulo'] == $codigo_articulo &&
				$trabajo['descripcion_articulo'] == $descripcion_articulo
			) {
				$trabajosFiltrados[$index] = $trabajo;
				$index .= 1;
			}
		}
	}

	$pdf->TablaArticulo($header, $trabajosFiltrados[0], 10, $y);
	$header = array('Tipos de trabajo', 'Posiciones');
	$pdf->TablaTrabajo($header, $trabajosFiltrados, 45, $y);
	$y += 10 * count($trabajosFiltrados) + 30;
}
sqlsrv_free_stmt($getResults);

$pdf->SetXY(10, $y);
$pdf->Cell(80, 7, 'OBSERVACIONES', 'B', 0, 'L');
$pdf->SetXY(10, $y + 7);
$pdf->MultiCell(80, 5, $_SESSION["observaciones"], 'LRTB', 'L', false);
$pdf->SetXY(90, $y);
$pdf->Cell(80, 7, 'FIRMA', 'B', 0, 'L');
$pdf->SetXY(90, $y + 7);
$pdf->Cell(80, 20, '', 1, 0, 'L');

$pdf->Output();
