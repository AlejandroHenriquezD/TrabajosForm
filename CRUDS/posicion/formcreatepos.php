<?php 
include "../sesion.php";
$_SESSION["Volver"] = "./posiciones.php";
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

    <h1>Posición</h1>
    <div id='div-form'>
        <form action="createpos.php" method="post" enctype="multipart/form-data">
            <label for="descripcion">Descripción</label>
            <input required type="text" id="descripcion" name="descripcion" placeholder="Descripción" />
            <button>Crear</button>
        </form>
    </div>
    <?php
    if(isset($_SESSION['confirmarAccion'])) {
        include "../confirmarAccion.php";
    }
    ?>
    <?php include "./menuPosiciones.php" ?>
</body>

</html>