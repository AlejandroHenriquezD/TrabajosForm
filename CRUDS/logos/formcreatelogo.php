<?php 
session_start(); 
$_SESSION["Volver"] = "../clientes/datoscliente.php";
?>
<!DOCTYPE html>
<html>

<head>
  <title>Formulario</title>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../cruds2.css">
</head>

<body>

  <h1>Logo</h1>
  <div id='div-form'>
    <form action="../../createlogo.php" method="post" enctype="multipart/form-data">
      <?php
      echo "
      <div class='datos'>
        <p class='tituloDatos'>Cliente</p>
        <p>" . $_SESSION["razon_social"] . "</p>
        <input type='hidden' value= '" . $_SESSION["id"] . "' id='id_cliente' name='id_cliente' />
      </div>
      ";
      ?>
      <label for="img">Imagen</label>
      <input required type="file" id="img" name="img" accept="image/*" />
      <button>Subir</button>
    </form>
  </div>
  <?php 
  if(isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "../clientes/menuCliente.php" ?>
</body>

</html>