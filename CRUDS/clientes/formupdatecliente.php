<?php
session_start();
$_SESSION["Volver"] = "./datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
  <title>Formulario</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../cruds.css">
</head>

<body>

  <h1>Cliente</h1>
  <?php
  if (isset($_POST["id"])) {
    $_SESSION["id_cliente"] = $_POST["id"];
    $_SESSION["nombre"] = urldecode(str_replace('+', ' ', $_POST["nombre"]));
    $_SESSION["telefono"] = urldecode(str_replace('+', ' ', $_POST["telefono"]));
    $_SESSION["correo"] = urldecode(str_replace('+', ' ', $_POST["correo"]));
    $_SESSION["dirección"] = urldecode(str_replace('+', ' ', $_POST["dirección"]));
    $_SESSION["cif_nif"] = urldecode(str_replace('+', ' ', $_POST["cif_nif"]));
    $_SESSION["numero_cliente"] = urldecode(str_replace('+', ' ', $_POST["numero_cliente"]));
    $_SESSION["razon_social"] = urldecode(str_replace('+', ' ', $_POST["razon_social"]));
  }
  
  echo "
  <form action='updatecliente.php' method='post' enctype='multipart/form-data'>
    <div id='formUpdate'>
      <div id='gridForm'>
        <div>
          <label for='nombre'>Nombre</label>
          <input name='id' type='hidden' value=" . $_SESSION["id_cliente"] . "></input>
          <input value='" . $_SESSION["nombre"] . "' type='text' id='nombre' name='nombre' placeholder='Nombre' />
        </div>
        <div>
          <label for='cif_nif'>CIF_NIF</label>
          " . $_SESSION["cif_nif"] . "
        </div>
        <div>
          <label for='telefono'>Telefono</label>
          <input value='" . $_SESSION["telefono"] . "' type='text' id='telefono' name='telefono' placeholder='Telefono' />
        </div>
        <div>
          <label for='correo'>Correo</label>
          <input value='" . $_SESSION["correo"] . "' type='text' id='correo' name='correo' placeholder='Correo' />
        </div>
        <div>
          <label for='dirección'>Dirección</label>
          <input value='" . $_SESSION["dirección"] . "' type='text' id='dirección' name='dirección' placeholder='Dirección' />
        </div>
        <div>
          <label for='numero_cliente'>Número de cliente</label>
  ";

  if (!isset($_SESSION['usuario'])) {
    echo $_SESSION["numero_cliente"] . "<input name='numero_cliente' type='hidden' value='" . $_SESSION["numero_cliente"] . "'></input>";
  } else {
    echo "<input required value='" . $_SESSION["numero_cliente"] . "' type='text' id='numero_cliente' name='numero_cliente' placeholder='Número de cliente' />";
  }
  echo "
        </div>
        <div>
          <label for='razon_social'>Razón social</label>
          <input required value='" . $_SESSION["razon_social"] . "' type='text' id='razon_social' name='razon_social' placeholder='Razón social' />
        </div>
      </div>
      <button>Editar</button>
    </div>
  </form>";
  if (isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "./menuCliente.php" ?>
</body>

</html>