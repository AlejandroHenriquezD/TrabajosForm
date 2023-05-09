<?php
session_start();
echo $_POST["numero_pedido"];
$pedido = explode('/', $_POST["numero_pedido"]);
$ejercicio_pedido = $pedido[0];
$serie_pedido = $pedido[1];
$numero_pedido = $pedido[2];
$id_boceto = $_POST["numero_boceto"]=="" ? null : $_POST["numero_boceto"];

$pedidos = json_decode(file_get_contents("http://localhost/trabajosformfront/BDReal/json/json_pedidos.php"), true);
$FechaPedido = null;
foreach ($pedidos as $ped) {
	if (
		$ped['EjercicioPedido'] == $ejercicio_pedido &&
		$ped['SeriePedido'] == $serie_pedido &&
		$ped['NumeroPedido'] == $numero_pedido
	) {
		$FechaPedido = $ped['FechaPedido'];
	}
}

include "../BDReal/numTienda.php";
$num_tienda = $tienda;

$pdf = "/pdf.php?ejercicio_pedido=". $ejercicio_pedido ."&serie_pedido=". $serie_pedido ."&numero_pedido=". $numero_pedido;

$id_boceto = $_POST["numero_boceto"] == "" ? null : $_POST["numero_boceto"];
$_SESSION["observaciones"] = $_POST["observaciones"];

foreach ($_POST['img-input'] as $grupo => $valor) {
  echo "El valor seleccionado es $valor del grupo $grupo <br>";

  var_dump($_FILES);
  $valor = explode('-', $valor);
  // echo $valor;

  $codigo_articulo = $valor[1];
  $descripcion_articulo = $valor[2];
  $id_tipo_articulo = $valor[3];
  $id_posicion = $valor[4];
  $id_tipo_trabajo = $valor[5];
  $id_logo = $valor[6] == "0" ? null : $valor[6];


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

  $sql = "INSERT INTO trabajos (
      ejercicio_pedido, 
      serie_pedido, 
      numero_pedido, 
      FechaPedido, 
      num_tienda, 
      codigo_articulo, 
      descripcion_articulo, 
      id_tipo_articulo, 
      id_tipo_trabajo, 
      id_posicion, 
      id_logo, 
      id_boceto,
      pdf
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
  }

  mysqli_stmt_bind_param(
    $stmt,
    "isiiiisiiiiis",
    $ejercicio_pedido,
    $serie_pedido,
    $numero_pedido,
    $FechaPedido,
    $num_tienda,
    $codigo_articulo,
    $descripcion_articulo,
    $id_tipo_articulo,
    $id_tipo_trabajo,
    $id_posicion,
    $id_logo,
    $id_boceto,
    $pdf
  );

  mysqli_stmt_execute($stmt);
}
header("location:pdf.php?ejercicio_pedido=" . $ejercicio_pedido . "&serie_pedido=" . $serie_pedido . "&numero_pedido=" . $numero_pedido);
// echo $_POST['observaciones'];
