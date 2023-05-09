<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedidos</title>
  <link rel="shortcut icon" href="../../frontend/favicon.png">
  <link rel="stylesheet" href="../../cruds.css">
</head>

<body>

  <?php
  $serverName = "192.168.0.23\SQLEXIT,1433";
  $connectionOptions = array(
    "Database" => "ExitERP0415",
    "Uid" => "programacion",
    "PWD" => "CU_2023",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
  );
  $conn = sqlsrv_connect($serverName, $connectionOptions);

  $sql = "SELECT    
            EjercicioPedido,
            SeriePedido,
            NumeroPedido,
            FechaPedido,
            IdDelegacion,
            CodigoCliente,
            CifDni,
            RazonSocial,
            Nombre,
            Domicilio,
            CodigoPostal,
            Municipio,
            Email1,
            Telefono,
            StatusPedido,
            EX_Serigrafiado
          FROM PedidoVentaCabecera
          WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1";
  
  if(!isset($_SESSION['usuario'])) {
    include "../../BDReal/numTienda.php";
    $sql .= "AND IdDelegacion = '$tienda'";
  }

  $getResults = sqlsrv_query($conn, $sql);


  echo "<h1>PEDIDOS PENDIENTES</h1>";
  echo "<table>
          <tr>
            <th>Ejercicio</th>
            <th>Serie</th>
            <th>Numero</th>
            <th>Codigo Cliente</th>
            <th>Razon Social</th>
            <th>Acciones</th>
          </tr>";
  while ($top = sqlsrv_fetch_array($getResults)) {

    echo "
      <tr class='fila'>
        <td>" . $top["EjercicioPedido"] . "</td>
        <td>" . $top["SeriePedido"] . "</td>
        <td>" . $top["NumeroPedido"] . "</td>
        <td>" . $top["CodigoCliente"] . "</td>
        <td>" . $top["RazonSocial"] . "</td>
        <td> 
          <form action='formupdatepedido.php' method='post'> 
            <input name='ejercicio_pedido[]' type='hidden' value=" . $top["EjercicioPedido"] . "></input> 
            <input name='serie_pedido[]' type='hidden' value=" . $top["SeriePedido"] . "></input> 
            <input name='numero_pedido[]' type='hidden' value=" . $top["NumeroPedido"] . "></input> 

            <input name='CodigoCliente[]' type='hidden' value=" . $top["CodigoCliente"] . "></input> 
            <button>Añadir Boceto<ion-icon name='create'></button> 
          </form>

          <form action='formañadirpdf.php' method='post'> 
            <input name='ejercicio_pedido[]' type='hidden' value=" . $top["EjercicioPedido"] . "></input> 
            <input name='serie_pedido[]' type='hidden' value=" . $top["SeriePedido"] . "></input> 
            <input name='numero_pedido[]' type='hidden' value=" . $top["NumeroPedido"] . "></input> 

            <input name='CodigoCliente[]' type='hidden' value=" . $top["CodigoCliente"] . "></input> 
            <button>Añadir Orden Trabajo<ion-icon name='create'></button> 
          </form>
        </td>
      </tr>
    ";
  }
  echo "</table>"
  ?>
  <?php include "./menuPedidos.php" ?>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>