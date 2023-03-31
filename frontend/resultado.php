<?php
foreach ($_POST['img-select'] as $valor) {
  echo "El valor seleccionado es $valor <br>";

  var_dump($_FILES);
  $valor = explode('-', $valor);

  $id_posicion = $valor[4];
  $id_articulo = $valor[1];
  $id_tipo_trabajo = $valor[2];
  $id_pedido = 1;
  $id_logo = $valor[5];
  $id_tipo_articulo = $valor[3];
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

  echo "Registro Guardado.";
}
?>