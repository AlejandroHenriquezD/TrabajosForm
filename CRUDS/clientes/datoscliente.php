<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Clientes</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
</head>
<body>
  <?php 
  echo "
  <p>Nombre: " . $_POST["nombre"] . "</p>
  <p>Teléfono: " . $_POST["telefono"] . "</p>
  <p>Correo: " . $_POST["correo"] . "</p>
  <p>Dirección: " . $_POST["dirección"] . "</p>
  <p>Cif/Nif: " . $_POST["cif_nif"] . "</p>
  <p>Número cliente: " . $_POST["numero_cliente"] . "</p>
  <p>Razón social: " . $_POST["razon_social"] . "</p>
  ";
  ?>
  <?php include "./menuCliente.php" ?>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>