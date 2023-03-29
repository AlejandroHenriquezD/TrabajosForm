<?php
foreach ($_POST['img-select'] as $valor) {
  echo "El valor seleccionado es $valor <br>";

  var_dump($_FILES);
  $tiposPosiciones = array(
    0 => "Pecho izquierdo",
    1 => "Pecho derecho",
    2 => "Fuera bolsillo",
    3 => "Dentro bolsillo",
    4 => "Manga izquierda",
    5 => "Manga derecha",
    6 => "Espalda"
  );
  $valor = explode('-', $valor);

  $posicion = $tiposPosiciones[$valor[4]];
  $id_articulo = $valor[1];
  $id_tipo_trabajo = $valor[2];
  $id_pedido = 1;
  $id_logo = $valor[5];
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

  $sql = "INSERT INTO trabajos (posicion, id_articulo, id_tipo_trabajo, id_pedido, id_logo) VALUES (?,?,?,?,?)";

  $stmt = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($stmt, $sql)) {
    die(mysqli_errno($conn));
  }

  mysqli_stmt_bind_param(
    $stmt,
    "siiii",
    $posicion,
    $id_articulo,
    $id_tipo_trabajo,
    $id_pedido,
    $id_logo
  );

  mysqli_stmt_execute($stmt);

  echo "Registro Guardado.";
}
?>