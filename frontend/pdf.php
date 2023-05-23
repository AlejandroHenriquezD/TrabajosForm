<?php
session_start();
require('../fpdf/fpdf.php');

$ejercicio_pedido = $_GET["ejercicio_pedido"];
$serie_pedido = $_GET["serie_pedido"];
$numero_pedido = $_GET["numero_pedido"];
$pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
$pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);

class PDF extends FPDF
{

	function AgregarFuente()
	{
		$this->AddFont('SourceSansPro', '', 'SourceSansPro-Regular.php');
		$this->AddFont('SourceSansPro', 'B', 'SourceSansPro-Bold.php');
	}

	public static $nombre_tienda;
	public static $calle;
	public static $localidad;
	public static $codigo_postal;
	public static $municipio;
	public static $n_ped_venta;
	public static $fecha_pedido;
	public static $CifDni;
	public static $RazonSocial;
	public static $NombreCliente;
	public static $Domicilio;
	public static $CodigoPostal;
	public static $Municipio;
	public static $Email;
	public static $Telefono;

	function Header()
	{
		if (
			!isset(self::$nombre_tienda) &&
			!isset(self::$calle) &&
			!isset(self::$localidad) &&
			!isset(self::$codigo_postal) &&
			!isset(self::$municipio) &&
			!isset(self::$n_ped_venta) &&
			!isset(self::$fecha_pedido)
		) {

			include "../BDReal/numTienda.php";
			include "../BDReal/domicilios.php";

			$ejercicio_pedido = $_GET["ejercicio_pedido"];
			$serie_pedido = $_GET["serie_pedido"];
			$numero_pedido = $_GET["numero_pedido"];
			$pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);

			self::$nombre_tienda = utf8_decode($nombre);
			self::$calle = utf8_decode("Domicilio: " . $domicilio[array_search($tienda, $almacen)][0]);
			self::$localidad = utf8_decode("Localidad: " . $domicilio[array_search($tienda, $almacen)][1]);
			self::$codigo_postal = utf8_decode("Código Postal: " . $domicilio[array_search($tienda, $almacen)][2]);
			self::$municipio = utf8_decode("Municipio: " . $domicilio[array_search($tienda, $almacen)][3]);
			self::$n_ped_venta = utf8_decode($ejercicio_pedido . '/' . $serie_pedido . '/' . $numero_pedido);
			foreach ($pedidos as $pedido) {
				if (
					$pedido['EjercicioPedido'] == $ejercicio_pedido &&
					$pedido['SeriePedido'] == $serie_pedido &&
					$pedido['NumeroPedido'] == $numero_pedido
				) {
					// $this->SetXY(150, 21);
					self::$fecha_pedido = utf8_decode(substr($pedido['FechaPedido']['date'], 0, 10));
					self::$CifDni = utf8_decode($pedido['CifDni']);
					self::$RazonSocial = utf8_decode($pedido['RazonSocial']);
					self::$NombreCliente = utf8_decode($pedido['Nombre']);
					self::$Domicilio = utf8_decode($pedido['Domicilio']);
					self::$CodigoPostal = utf8_decode($pedido['CodigoPostal']);
					self::$Municipio = utf8_decode($pedido['Municipio']);
					self::$Email = utf8_decode($pedido['Email1']);
					self::$Telefono = utf8_decode($pedido['Telefono']);
				}
			}
		}

		$this->Image('../login/cu.png', 10, 14, 50);

		$this->SetXY(70, 10);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(0, 1, utf8_decode('Datos de la tienda'), 0, 1, 'L');

		$this->SetXY(70, 15);
		$this->SetFont('SourceSansPro', '', 9);
		$this->Cell(0, 1, self::$nombre_tienda, 0, 1, 'L');
		$this->SetXY(110, 10);
		$this->Cell(0, 1, self::$localidad, 0, 1, 'L');
		$this->SetXY(110, 15);
		$this->Cell(0, 1, self::$codigo_postal, 0, 1, 'L');

		$this->SetXY(150, 10);
		$this->Cell(0, 1, self::$calle, 0, 1, 'L');
		$this->SetXY(150, 15);
		$this->Cell(0, 1, self::$municipio, 0, 1, 'L');

		$this->Line(70, 20, 200, 20);

		$this->SetXY(70, 25);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(0, 1, utf8_decode('Datos del pedido'), 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 25);
		$this->Cell(0, 1, utf8_decode('Fecha pedido: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(130, 25);
		$this->Cell(0, 1, self::$fecha_pedido, 0, 1, 'L');
		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(150, 25);
		$this->Cell(0, 1, utf8_decode('P. venta: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->SetXY(163, 25);
		$this->Cell(0, 1, self::$n_ped_venta, 0, 1, 'L');

		$this->Line(70, 30, 200, 30);

		$this->SetXY(70, 35);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(0, 1, utf8_decode('Datos del cliente'), 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(70, 40);
		$this->Cell(0, 1, utf8_decode('Teléfono: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(84, 40);
		$this->Cell(0, 1, self::$Telefono, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(70, 45);
		$this->Cell(0, 1, utf8_decode('Código Postal: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(90, 45);
		$this->Cell(0, 1, self::$CodigoPostal, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(70, 50);
		$this->Cell(0, 1, utf8_decode('Cif/Dni: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(81, 50);
		$this->Cell(0, 1, self::$CifDni, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 35);
		$this->Cell(0, 1, utf8_decode('Razón Social: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(129, 35);
		$this->Cell(0, 1, self::$RazonSocial, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 40);
		$this->Cell(0, 1, utf8_decode('Nombre: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(124, 40);
		$this->Cell(0, 1, self::$NombreCliente, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 45);
		$this->Cell(0, 1, utf8_decode('Domicilio: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(125, 45);
		$this->Cell(0, 1, self::$Domicilio, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 50);
		$this->Cell(0, 1, utf8_decode('Municipio: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(125, 50);
		$this->Cell(0, 1, self::$Municipio, 0, 1, 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$this->SetXY(110, 55);
		$this->Cell(0, 1, utf8_decode('Email: '), 0, 1, 'L');
		$this->SetFont('SourceSansPro', 'B', 9);
		$this->SetXY(120, 55);
		$this->Cell(0, 1, self::$Email, 0, 1, 'L');
	}

	function footer()
	{
		$this->SetY(-15);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(0, 1, utf8_decode('Página ' . $this->PageNo() . ' de {nb}'), 0, 0, 'R');
	}

	public $posY = 70;

	function SaltarPagina($size)
	{
		if ($this->posY + $size > 277) {
			$this->AddPage();
			$this->posY = 65;
		}
	}

	function firma()
	{
		$this->SaltarPagina(100);
		$this->SetXY(25, 235);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(70, 12, self::$RazonSocial, 'B', 0, 'L', false);
		$this->SetXY(10, 247);
		$this->SetFont('SourceSansPro', '', 11);
		$this->Cell(30, 20, 'FIRMA', '', 0, 'L', false);
		$this->SetXY(25, 247);
		$this->Cell(70, 20, '', 1, 0, 'L');
	}

	function TablaArticulo($header, $trabajo, $x)
	{
		$this->SetXY($x, $this->posY);
		$this->SetFont('SourceSansPro', 'B', 9);
		foreach ($header as $col)
			$this->MultiCell(85, 7, utf8_decode($col), 'B', 'L');

		$this->SetFont('SourceSansPro', '', 9);
		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajo['id_tipo_articulo']), true);

		$this->SetXY($x, $this->posY + 7);
		$this->Cell(25, 19, '', 0, 0, 'C');
		$this->Image('.' . $tipo_articulo['img'], $x + 3, $this->posY + 8, 20);
	}

	function TablaTrabajo($header, $trabajos, $x)
	{

		$possum = 0;
		$this->SetXY($x, $this->posY + 7);
		$this->SetFont('SourceSansPro', 'B', 9);
		foreach ($header as $col)
			$this->Cell(25, 7, $col, 1, 0, 'C', true);

		$this->SetFont('SourceSansPro', '', 9);
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
			$this->SetXY($x, $this->posY + 14 + $possum);

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

	function TablaReferencia($header, $titulotabla, $articulos, $nombretabla, $x)
	{
		$this->SetXY($x, $this->posY);
		$this->SetFont('SourceSansPro', 'B', 11);
		$this->Cell(30, 12, $titulotabla, 0, 0, 'L', false);
		$this->posY += 12;


		$color = 'blanco';
		if ($nombretabla == 'tabla1') {
			$this->SetXY($x, $this->posY);
			$this->SetFont('SourceSansPro', 'B', 9);
			foreach ($header as $col)
				$this->Cell(30, 7, utf8_decode($col), 1, 0, 'C', true);

			$this->SetFont('SourceSansPro', '', 9);

			$sum = 7;
			foreach ($articulos as $articulo) {
				if ($color == 'blanco') {
					$this->SetFillColor(240, 240, 240);
					$color = 'gris';
				} else {
					$this->SetFillColor(190, 190, 190);
					$color = 'blanco';
				}
				$this->SetXY($x, $this->posY + $sum);
				$this->Cell(30, 7, $articulo['CodigoArticulo'], 1, 0, 'C', true);
				$this->Cell(30, 7, $articulo['CodigoColor_'], 1, 0, 'C', true);
				$this->Cell(30, 7, $articulo['CodigoTalla'], 1, 0, 'C', true);
				$this->Cell(30, 7, round($articulo['Unidades'], 0, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$sum += 7;
			}
		} else if ($nombretabla == 'tabla2') {
			$this->SetXY($x, $this->posY);
			$this->SetFont('SourceSansPro', 'B', 9);
			foreach ($header as $col) {
				if ($col != "DESCRIPCIÓN") {
					$this->Cell(25, 7, utf8_decode($col), 1, 0, 'C', true);
				} else {
					$this->Cell(90, 7, utf8_decode($col), 1, 0, 'C', true);
				}
			}

			$this->SetFont('SourceSansPro', '', 9);

			$sum = 7;
			foreach ($articulos as $articulo) {
				if ($color == 'blanco') {
					$this->SetFillColor(240, 240, 240);
					$color = 'gris';
				} else {
					$this->SetFillColor(190, 190, 190);
					$color = 'blanco';
				}
				$this->SetXY($x, $this->posY + $sum);
				$this->Cell(25, 7, $articulo['CodigoArticulo'], 1, 0, 'C', true);
				$this->Cell(90, 7, utf8_decode($articulo['DescripcionArticulo']), 1, 0, 'C', true);
				$this->Cell(25, 7, round($articulo['Unidades'], 0, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$this->Cell(25, 7, round($articulo['PrecioNeto'], 2, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
				$this->Cell(25, 7, round($articulo['PrecioNeto'] * $articulo['Unidades'], 2, PHP_ROUND_HALF_DOWN), 1, 0, 'C', true);
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
$pdf->AddPage();

$pdf->SetY($pdf->posY);
$pdf->SetFont('SourceSansPro', 'B', 15);
$pdf->Cell(0, 1, utf8_decode('Orden de trabajo para Serigrafía'), 0, 1, 'C');
$pdf->posY += 15;

$articulos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos.php"), true);
$articulos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_articulos.php"), true);

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
		-- AND CodigoAlmacen = '06'
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
$observaciones = '';
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
			$trabajoreplaced = str_replace('[guion]', '-', stripslashes($trabajo['descripcion_articulo']));
			// echo $trabajoreplaced . "<br>";
			// echo $descripcion_articulo . "<br><br>";
			if (
				$trabajo['codigo_articulo'] == $codigo_articulo &&
				// str_contains($descripcion_articulo, stripslashes($trabajo['descripcion_articulo']))
				$trabajoreplaced == $descripcion_articulo
			) {

				$trabajosFiltrados[$index] = $trabajo;
				$index += 1;
			}
		}
	}
	if (isset($trabajosFiltrados[0])) {
		// $pdf->SaltarPagina($pdf->posY += 10 * count($trabajosFiltrados) + 30);
		if ($pos == "izquierda") {
			$pdf->TablaArticulo($header, $trabajosFiltrados[0], 10);
			$header = array('Tipos de trabajo', 'Posiciones');
			$pdf->TablaTrabajo($header, $trabajosFiltrados, 45);
			$countIzda = count($trabajosFiltrados);
			$pos = "derecha";
		} else if ($pos == "derecha") {
			$pdf->TablaArticulo($header, $trabajosFiltrados[0], $x + 10);
			$header = array('Tipos de trabajo', 'Posiciones');
			$pdf->TablaTrabajo($header, $trabajosFiltrados, $x + 45);
			if ($countIzda > count($trabajosFiltrados)) {
				$pdf->posY += 10 * $countIzda + 35;
			} else {
				$pdf->posY += 10 * count($trabajosFiltrados) + 35;
			}
			$pos = "izquierda";
		}
		$observaciones = $trabajosFiltrados[0]['observaciones'];
	}
}

if ($pos == "derecha") {
	$pdf->posY += 10 * $countIzda + 35;
}

$pdf->SaltarPagina(100);
$pdf->SetXY(10, $pdf->posY);
$pdf->SetFont('SourceSansPro', 'B', 11);
$pdf->Cell(120, 12, 'OBSERVACIONES', 'B', 0, 'L', false);
$pdf->SetXY(10, $pdf->posY + 12);
$pdf->SetFont('SourceSansPro', '', 11);
$pdf->MultiCell(120, 5, utf8_decode($observaciones), 'LRTB', 'L', false);

$pdf->SaltarPagina(999);
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
		-- AND CodigoAlmacen = '06'
		";

$getResults = sqlsrv_query($conn, $tsql);

$data2 = [];

while ($articulo = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
	$data2[] = $articulo;
}

sqlsrv_free_stmt($getResults);

$pdf->SaltarPagina(7 * count($data2) + 15);

$header = array('CÓDIGO ARTÍCULO', 'COLOR', 'TALLA', 'CANTIDAD');
$pdf->TablaReferencia($header, utf8_decode('ARTÍCULOS'), $data2, 'tabla1', 10);

$pdf->posY += 7 * count($data2) + 15;

$conn3 = sqlsrv_connect($serverName, $connectionOptions);

$tsql3 = "SELECT
            CodigoArticulo,
            Unidades,
            PrecioNeto,
						DescripcionArticulo
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

$pdf->SaltarPagina(7 * count($data3) + 15);

$header = array('REFERENCIA', 'DESCRIPCIÓN', 'UNIDADES', 'PRECIO UNIDAD', 'TOTAL IMPORTE');
$pdf->TablaReferencia($header, utf8_decode('EPÍGRAFES'), $data3, 'tabla2', 10);

$pdf->posY += 7 * count($data3) + 15;

$pdf->firma();

$pdf->AliasNbPages();

$pdf->Output('D','orden_trabajo_pedido_'. $ejercicio_pedido . '_' . $serie_pedido . '_' . $numero_pedido . '.pdf');
// $pdf->Output();
