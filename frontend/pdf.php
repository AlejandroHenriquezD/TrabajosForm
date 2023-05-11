<?php
session_start();
require('../fpdf/fpdf.php');
// include_once "../BDReal/numTienda.php";

$ejercicio_pedido = $_GET["ejercicio_pedido"];
$serie_pedido = $_GET["serie_pedido"];
$numero_pedido = $_GET["numero_pedido"];
$pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);

class PDF extends FPDF
{

	function AgregarFuente()
	{
		$this->AddFont('SourceSansPro', '', 'SourceSansPro-Regular.php');
		$this->AddFont('SourceSansPro', 'B', 'SourceSansPro-Bold.php');
	}

	function Cabecera()
	{
		include_once "../BDReal/numTienda.php";
		include_once "../BDReal/domicilios.php";

		$ejercicio_pedido = $_GET["ejercicio_pedido"];
		$serie_pedido = $_GET["serie_pedido"];
		$numero_pedido = $_GET["numero_pedido"];
		$pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);

		$this->Image('../login/cu.png', 10, 10, 45);

		$this->SetXY(60, 11);
		$this->SetFont('SourceSansPro', 'B', 10);
		$this->Cell(0, 1, utf8_decode('Datos de la tienda'), 0, 1, 'L');
		$this->SetXY(60, 16);
		$this->SetFont('SourceSansPro', '', 8);
		$this->Cell(0, 1, utf8_decode($nombre), 0, 1, 'L');

		$this->SetXY(60, 21);
		$this->Cell(0, 1, utf8_decode("Domicilio: " . $domicilio[array_search($tienda, $almacen)][0]), 0, 1, 'L');
		$this->SetXY(100, 11);
		$this->Cell(0, 1, utf8_decode("Localidad: " . $domicilio[array_search($tienda, $almacen)][1]), 0, 1, 'L');
		$this->SetXY(100, 16);
		$this->Cell(0, 1, utf8_decode("Código Postal: " . $domicilio[array_search($tienda, $almacen)][2]), 0, 1, 'L');
		$this->SetXY(100, 21);
		$this->Cell(0, 1, utf8_decode("Municipio: " . $domicilio[array_search($tienda, $almacen)][3]), 0, 1, 'L');

		$this->SetXY(150, 11);
		$this->SetFont('SourceSansPro', 'B', 10);
		$this->Cell(0, 1, utf8_decode('Datos del pedido'), 0, 1, 'R');
		$this->SetXY(150, 16);
		$this->SetFont('SourceSansPro', '', 8);
		$this->Cell(0, 1, utf8_decode('Número pedido de venta: ' . $ejercicio_pedido . '/' . $serie_pedido . '/' . $numero_pedido), 0, 1, 'R');
		foreach ($pedidos as $pedido) {
			if (
				$pedido['EjercicioPedido'] == $ejercicio_pedido &&
				$pedido['SeriePedido'] == $serie_pedido &&
				$pedido['NumeroPedido'] == $numero_pedido
			) {
				$this->SetXY(150, 21);
				$this->Cell(0, 1, 'Fecha pedido: ' . substr($pedido['FechaPedido']['date'], 0, 10), 0, 1, 'R');
			}
		}
	}

	function TablaArticulo($header, $trabajo, $x, $y)
	{
		$this->SetXY($x, $y);
		$this->SetFont('SourceSansPro', 'B', 8);
		foreach ($header as $col)
			$this->MultiCell(85, 7, utf8_decode($col), 'B', 'L');

		$this->SetFont('SourceSansPro', '', 8);
		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajo['id_tipo_articulo']), true);

		$this->SetXY($x, $y + 7);
		$this->Cell(25, 19, '', 0, 0, 'C');
		$this->Image('.' . $tipo_articulo['img'], $x + 3, $y + 8, 20);
	}

	function TablaTrabajo($header, $trabajos, $x, $y)
	{

		$possum = 0;
		$this->SetXY($x, $y);
		$this->SetFont('SourceSansPro', 'B', 8);
		// $this->SetFillColor(252, 158, 156);
		foreach ($header as $col)
			$this->Cell(25, 7, $col, 1, 0, 'C', true);

		$this->SetFont('SourceSansPro', '', 8);
		$this->SetFillColor(180, 252, 156);
		$tipo_trabajosFiltrados = array();

		foreach ($trabajos as $trabajo) {
			$tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajo['id_tipo_trabajo']), true);

			if (!in_array($tipo_trabajo, $tipo_trabajosFiltrados)) {
				array_push($tipo_trabajosFiltrados, $tipo_trabajo);
			}
		}
		$color = 'blanco';
		foreach ($tipo_trabajosFiltrados as $tipo_trabajo) {

			$posicionesFiltradas = array();
			foreach ($trabajos as $trabajo) {
				if ($tipo_trabajo['id'] == $trabajo['id_tipo_trabajo']) {
					$posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajo['id_posicion']), true);
					array_push($posicionesFiltradas, $posicion);
				}
			}
			$this->SetXY($x, $y + 7 + $possum);

			if ($color == 'blanco') {
				$this->SetFillColor(240, 240, 240);
				$color = 'gris';
			} else {
				$this->SetFillColor(190, 190, 190);
				$color = 'blanco';
			}

			$this->Cell(25, 10 * count($posicionesFiltradas), utf8_decode($tipo_trabajo['nombre']), 1, 0, 'C', true);
			foreach ($posicionesFiltradas as $posicion) {
				$this->SetX($x + 25);
				$this->Cell(25, 10, utf8_decode($posicion['descripcion']), 1, 0, 'C', true);
				$this->Ln();
				$possum += 10;
			}
		}
		$possum = 0;
		$this->SetFillColor(180, 252, 156);
	}

	function TablaReferencia($header, $articulos, $nombretabla, $x, $y)
	{
		$this->SetXY($x, $y);
		$this->SetFont('SourceSansPro', 'B', 8);
		foreach ($header as $col)
			$this->Cell(30, 7, $col, 1, 0, 'C', true);

		$this->SetFont('SourceSansPro', '', 8);
		$this->Ln();

		$sum = 7;
		$color = 'blanco';
		if ($nombretabla == 'tabla1') {
			foreach ($articulos as $articulo) {
				if ($color == 'blanco') {
					$this->SetFillColor(240, 240, 240);
					$color = 'gris';
				} else {
					$this->SetFillColor(190, 190, 190);
					$color = 'blanco';
				}
				$this->SetXY($x, $y + $sum);
				$this->Cell(30, 7, $articulo['CodigoArticulo'], 1, 0, 'C', true);
				$this->Cell(30, 7, $articulo['CodigoColor_'], 1, 0, 'C', true);
				$this->Cell(30, 7, $articulo['CodigoTalla'], 1, 0, 'C', true);
				$this->Cell(30, 7, round($articulo['Unidades'], 0, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$sum += 7;
			}
		} else if ($nombretabla == 'tabla2') {
			foreach ($articulos as $articulo) {
				if ($color == 'blanco') {
					$this->SetFillColor(240, 240, 240);
					$color = 'gris';
				} else {
					$this->SetFillColor(190, 190, 190);
					$color = 'blanco';
				}
				$this->SetXY($x, $y + $sum);
				$this->Cell(30, 7, $articulo['CodigoArticulo'], 1, 0, 'C', true);
				$this->Cell(30, 7, round($articulo['Unidades'], 0, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$this->Cell(30, 7, round($articulo['PrecioNeto'], 2, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$this->Cell(30, 7, round($articulo['PrecioNeto'] * $articulo['Unidades'], 2, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$sum += 7;
			}
		}
		$this->SetFillColor(180, 252, 156);
	}
}

$pdf = new PDF('P', 'mm', 'A4');
// Títulos de las columnas

// Carga de datos
$trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
$pdf->AgregarFuente();
$pdf->SetFillColor(180, 252, 156);
$pdf->SetFont('SourceSansPro', '', 8);
$pdf->AddPage();

$pdf->Cabecera();

$articulos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos.php"), true);

$y = 36;

include_once "../BDReal/conexion_exit.php";

$tsql = "SELECT DISTINCT
						CodigoAlmacen,
						CodigoArticulo,
						SeriePedido,
						NumeroPedido,
						DescripcionArticulo
		FROM PedidoVentaLineas
		WHERE EjercicioPedido = '$ejercicio_pedido'
		AND SeriePedido = '$serie_pedido'
		AND NumeroPedido = '$numero_pedido'
		AND (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
		AND EX_Serigrafiado = -1
		AND TipoArticulo = 'M'
		AND CodigoAlmacen = '06'
		";


$getResults = sqlsrv_query($conn, $tsql);

$data = [];

while ($articulo = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	$data[] = $articulo;
}

sqlsrv_free_stmt($getResults);

$pos = "izquierda";
$x = 100;
$countIzda = 0;
foreach ($data as $articulo) {

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
				stripslashes($trabajo['descripcion_articulo']) == $descripcion_articulo
			) {

				$trabajosFiltrados[$index] = $trabajo;
				$index .= 1;
			}
		}
	}
	if (isset($trabajosFiltrados[0])) {

		if ($pos == "izquierda") {
			$pdf->TablaArticulo($header, $trabajosFiltrados[0], 10, $y);
			$header = array('Tipos de trabajo', 'Posiciones');
			$pdf->TablaTrabajo($header, $trabajosFiltrados, 45, $y + 7);
			$countIzda = count($trabajosFiltrados);
			$pos = "derecha";
		} else if ($pos == "derecha") {
			$pdf->TablaArticulo($header, $trabajosFiltrados[0], $x + 10, $y);
			$header = array('Tipos de trabajo', 'Posiciones');
			$pdf->TablaTrabajo($header, $trabajosFiltrados, $x + 45, $y + 7);
			if ($countIzda > count($trabajosFiltrados)) {
				$y += 10 * $countIzda + 30;
			} else {
				$y += 10 * count($trabajosFiltrados) + 30;
			}
			$pos = "izquierda";
		}
	}
}

if ($pos == "derecha") {
	$y += 10 * $countIzda + 30;
}

$tsql = "SELECT
				CodigoArticulo,
				CodigoColor_,
   	 		CodigoTalla,
				Unidades
		FROM PedidoVentaLineas
		WHERE EjercicioPedido = $ejercicio_pedido
		AND SeriePedido = '$serie_pedido'
		AND NumeroPedido = $numero_pedido
		AND (CodigoArticulo NOT LIKE ('6000%') OR CodigoArticulo NOT LIKE ('6001%') OR CodigoArticulo NOT LIKE ('6002%') OR CodigoArticulo NOT LIKE ('6003%'))
		AND EX_Serigrafiado = -1
		AND TipoArticulo = 'M'
		AND CodigoAlmacen = '06'
		";

$getResults = sqlsrv_query($conn, $tsql);

$data2 = [];

while ($articulo = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	$data2[] = $articulo;
}

sqlsrv_free_stmt($getResults);

$header = array('REF.PREND', 'COLOR', 'TALLA', 'CANTIDAD');
$pdf->TablaReferencia($header, $data2, 'tabla1', 10, $y);

$y += 7 * count($data2) + 20;

$conn3 = sqlsrv_connect($serverName, $connectionOptions);

$tsql3 = "SELECT
            CodigoArticulo,
            Unidades,
            PrecioNeto
        FROM PedidoVentaLineas
        WHERE 
        (CodigoArticulo LIKE ('6000%') OR CodigoArticulo LIKE ('6001%') OR CodigoArticulo LIKE ('6002%') OR CodigoArticulo LIKE ('6003%'))
        AND LEN(CodigoArticulo) = 7
				AND EjercicioPedido = $ejercicio_pedido
        AND SeriePedido = '$serie_pedido'
        AND NumeroPedido = $numero_pedido
        ";

$getResults3 = sqlsrv_query($conn3, $tsql3);

$data3 = [];

while ($articulo = sqlsrv_fetch_array($getResults3, SQLSRV_FETCH_ASSOC)) {
	$data3[] = $articulo;
}

sqlsrv_free_stmt($getResults3);

$header = array('REF.ESTAMPA', 'UNIDADES', 'PRECIO UNIDADES', 'TOTAL IMPORTE');
$pdf->TablaReferencia($header, $data3, 'tabla2', 10, $y);

$y += 7 * count($data3) + 20;


$pdf->SetXY(10, $y);
$pdf->SetFont('SourceSansPro', 'B', 8);
$pdf->Cell(80, 7, 'OBSERVACIONES', 'B', 0, 'L', false);
$pdf->SetXY(10, $y + 7);
$pdf->SetFont('SourceSansPro', '', 8);
$pdf->MultiCell(80, 5, utf8_decode($_SESSION["observaciones"]), 'LRTB', 'L', false);
$pdf->SetXY(90, $y);
$pdf->SetFont('SourceSansPro', 'B', 8);
$pdf->Cell(80, 7, 'FIRMA', 'B', 0, 'L', false);
$pdf->SetXY(90, $y + 7);
$pdf->Cell(80, 20, '', 1, 0, 'L');

$pdf->Output('D','orden_trabajo_pedido_'. $ejercicio_pedido . '_' . $serie_pedido . '_' . $numero_pedido . '.pdf');



