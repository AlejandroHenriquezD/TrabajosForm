<?php
$id_pedido = $_POST['selectPedido'][0];
$id_boceto = $_POST["selectBoceto"][0];

echo "<form action='pdf.php'>
        <button >Ver PDF</button>
      </form>";
foreach ($_POST['img-input'] as $grupo => $valor) {
  echo "El valor seleccionado es $valor del grupo $grupo <br>";

  var_dump($_FILES);
  $valor = explode('-', $valor);

  $id_posicion = $valor[4];
  $id_articulo = $valor[1];
  $id_tipo_trabajo = $valor[3];
  $id_logo = $valor[5];
  $id_tipo_articulo = $valor[2];
  // $id_boceto = $valor[1];
  echo($valor[1]);

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

  $sql = "INSERT INTO trabajos (id_posicion, id_articulo, id_tipo_trabajo, id_pedido, id_logo, id_tipo_articulo, id_boceto) VALUES (?,?,?,?,?,?,?)";

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
  }

  mysqli_stmt_bind_param(
    $stmt,
    "siiiiii",
    $id_posicion,
    $id_articulo,
    $id_tipo_trabajo,
    $id_pedido,
    $id_logo,
    $id_tipo_articulo,
    $id_boceto
  );

  mysqli_stmt_execute($stmt);

  echo "Registro Guardado.";
  echo "<br/>";
}
echo $_POST['observaciones'];
?>