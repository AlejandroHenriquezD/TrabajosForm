<?php
$id_pedido = $_POST['selectPedido'][0];
foreach ($_POST['img-select'] as $valor) {
  // echo "El valor seleccionado es $valor <br>";

  // var_dump($_FILES);
  $valor = explode('-', $valor);

  $id_posicion = $valor[4];
  $id_articulo = $valor[1];
  $id_tipo_trabajo = $valor[2];
  $id_logo = $valor[5];
  $id_tipo_articulo = $valor[3];
  // echo($valor[1]);

  $host = "localhost";
  $dbname = "centraluniformes";
  $username = "root";
  $password = "";

  $conn = mysqli_connect(
    hostname: $host,
    username: $username,
    password: $password,
    database: $dbname
  );

  if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
  }

  $sql = "INSERT INTO trabajos (id_posicion, id_articulo, id_tipo_trabajo, id_pedido, id_logo, id_tipo_articulo) VALUES (?,?,?,?,?,?)";

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
  }

  mysqli_stmt_bind_param(
    $stmt,
    "siiiii",
    $id_posicion,
    $id_articulo,
    $id_tipo_trabajo,
    $id_pedido,
    $id_logo,
    $id_tipo_articulo
  );

  mysqli_stmt_execute($stmt);

  // echo "Registro Guardado.";
}


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
		$posicion = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
        $articulo = json_decode(file_get_contents("http://localhost/trabajosform/articulos/" . $trabajos[$p]['id_articulo']), true);
        $tipo_trabajo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
        $pedido = json_decode(file_get_contents("http://localhost/trabajosform/pedidos/" . $trabajos[$p]['id_pedido']), true);
		$tipo_articulo = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos/" . $trabajos[$p]['id_tipo_articulo']), true);
        $logo = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);

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
$trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);
$pdf->SetFont('Times','',11);
$pdf->AddPage();
$pdf->BasicTable($header,$trabajos);
$pdf->Output();
?>