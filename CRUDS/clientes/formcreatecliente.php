<?php session_start(); ?>
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

    <h1>Cliente</h1>

    <form action="createcliente.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre</label>
        <input required type="text" id="nombre" name="nombre" placeholder="Nombre" />

        <label for="telefono">Telefono</label>
        <input required type="text" id="telefono" name="telefono" placeholder="Telefono" />

        </br> 

        <button>Crear</button>
    </form>
    <?php include "./menuCliente.php" ?>
</body>

</html>