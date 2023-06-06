<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
  <title>Formulario</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../cruds.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body>

  <h1>Logo</h1>
  <?php
  if(isset($_POST["id"])) {
    $_SESSION["id_logo"] = $_POST["id"][0];
  }
  echo "
  <div id='div-form'>
    <form action='updatelogo.php' method='post' enctype='multipart/form-data'>
      <label for='obsoleto'>Obsoleto</label>
      <input name='id[]' type='hidden' value=" . $_SESSION["id_logo"] . "></input>
      <select name='obsoleto'>
        <option value='1' id='obsoleto' name='obsoleto'>Esta Obsoleto</option>
        <option value='0' id='obsoleto' name='obsoleto'>No Obsoleto</option>
      </select>
      <button>Editar</button>
    </form>
  </div>";
  if(isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "../clientes/menuCliente.php" ?>
</body>

</html>