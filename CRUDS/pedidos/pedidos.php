<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedidos de Venta</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
</head>

<body onload='filtrar();'>

  <?php

  $pedidos = array();
  if (isset($_SESSION['usuario'])) {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_todos.php"), true);
  } else {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
  }

  $host = "localhost";
  $dbname = "centraluniformes";
  $username = "root";
  $password = "";

  $conn1 = mysqli_connect(
    hostname: $host,
    username: $username,
    password: $password,
    database: $dbname
  );

  $serverName = "192.168.0.23\SQLEXIT,1433";
  $connectionOptions = array(
    "Database" => "ExitERP0415",
    "Uid" => "programacion",
    "PWD" => "CU_2023",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
  );
  $connSQLSERVER = sqlsrv_connect($serverName, $connectionOptions);

  for ($i = 0; $i < count($pedidos); $i++) {
    $sqlMercancia = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0
    AND PVC.IdDelegacion = '" . $pedidos[$i]['IdDelegacion'] . "' 
    AND PVC.EjercicioPedido = '" . $pedidos[$i]['EjercicioPedido'] . "'
    AND PVC.SeriePedido = '" . $pedidos[$i]["SeriePedido"] . "'
    AND PVC.NumeroPedido = '" . $pedidos[$i]["NumeroPedido"] . "'";

    $getResults = sqlsrv_query($connSQLSERVER, $sqlMercancia,array(), array( "Scrollable" => 'static' ));

    $row_count = sqlsrv_num_rows($getResults);


    $sql = "SELECT id_boceto,pdf,pdf_firmado FROM trabajos WHERE ejercicio_pedido = '" . $pedidos[$i]['EjercicioPedido'] . "' AND serie_pedido = '" . $pedidos[$i]["SeriePedido"] . "' AND numero_pedido ='" . $pedidos[$i]["NumeroPedido"] . "'";
    $result = mysqli_query($conn1, $sql);
    $row = mysqli_fetch_array($result);
    $estado = array();
    if (mysqli_num_rows($result) > 0) {
      //Comprobamos si el trabajo tiene boceto o no
      if ($row[0] != "") {

        //Hacemos la query del boceto para saber si esta firmado o no
        $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $row[0];
        $resultBoceto = mysqli_query($conn1, $sqlBoceto);
        $rowBoceto = mysqli_fetch_array($resultBoceto);

        // Si esta firmado y el pdf no es null estado correcto
        if ($rowBoceto["firmado"] == 1 && $row["pdf"] != "" && $row["pdf_firmado"] == 1 && $row_count > 0) {
          $estado = array('Estado' => "aceptar");
        } else {
          $estado = array('Estado' => "cancelar");
        }
      } else {
        $estado = array('Estado' => "cancelar");
      }
    } else {
      $estado = array('Estado' => "cancelar");
    }
    // FALTA LAS CONDICIONES PARA EN LAS QUE NO ESTA LISTA LA DOCUMENTACION Y AÑADIR SI LA MERCANCIA ESTA SERVIDA O NO


    $pedidos[$i] = array_merge($pedidos[$i], $estado);
  }
  $pedidos = json_encode($pedidos);
  echo "
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  function datosPedido(IdDelegacion, EjercicioPedido, SeriePedido, NumeroPedido, CodigoCliente, RazonSocial, Estado) {
    document.getElementById('IdDelegacion').value = IdDelegacion;
    document.getElementById('EjercicioPedido').value = EjercicioPedido;
    document.getElementById('SeriePedido').value = SeriePedido;
    document.getElementById('NumeroPedido').value = NumeroPedido;
    document.getElementById('CodigoCliente').value = CodigoCliente;
    document.getElementById('RazonSocial').value = RazonSocial;
    document.getElementById('Estado').value = Estado;
    document.getElementById('inputsOcultos').submit();
  }

  function filtrar() {
    var pedidos = $pedidos
    if(document.getElementById('filtro_serie').value != ''){
      var serie = document.getElementById('filtro_serie').value
      pedidos = pedidos.filter((pedidos) => pedidos.SeriePedido.toUpperCase().includes(serie.toUpperCase()));
    }
    if(document.getElementById('filtro_ejercicio').value != ''){
      var ejercicio = document.getElementById('filtro_ejercicio').value
      pedidos = pedidos.filter((pedidos) => pedidos.EjercicioPedido.toString().includes(ejercicio.toString()));
    }
    if(document.getElementById('filtro_numero').value != ''){
      var numero = document.getElementById('filtro_numero').value
      pedidos = pedidos.filter((pedidos) => pedidos.NumeroPedido.toString().includes(numero.toString()));
    }
    var tabla = '<table id=\"tablaClientes\"><tr><th>Tienda</th><th>Ejercicio</th><th>Serie</th><th>Número</th><th>Código cliente</th><th>Razón social</th><th>Acciones</th><th>Estado Documentación</th></tr>';
    for(pedido of pedidos) {
      tabla += '<tr class=\"fila\" onclick=\"datosPedido(\'' + pedido[\"IdDelegacion\"] + '\',\'' + pedido[\"EjercicioPedido\"] + '\',\'' + pedido[\"SeriePedido\"] + '\',\'' + pedido[\"NumeroPedido\"] + '\',\'' + pedido[\"CodigoCliente\"] + '\',\'' + pedido[\"RazonSocial\"] + '\',\'' + pedido[\"Estado\"] + '\')\">'
      tabla += '<td>' + pedido[\"IdDelegacion\"] + '</td>'
      tabla += '<td>' + pedido[\"EjercicioPedido\"] + '</td>'
      tabla += '<td>' + pedido[\"SeriePedido\"] + '</td>'
      tabla += '<td>' + pedido[\"NumeroPedido\"] + '</td>'
      tabla += '<td>' + pedido[\"CodigoCliente\"] + '</td>'
      tabla += '<td>' + pedido[\"RazonSocial\"] + '</td>'
      tabla += '<td><div class=\"td-botones\">'
      tabla += '<form action=\'formupdatepedido.php\' method=\'post\'>'
      tabla += '<input name=\'ejercicio_pedido[]\' type=\'hidden\' value=' + pedido[\"EjercicioPedido\"] + '></input>' 
      tabla += '<input name=\'serie_pedido[]\' type=\'hidden\' value=' + pedido[\"SeriePedido\"] + '></input>'
      tabla += '<input name=\'numero_pedido[]\' type=\'hidden\' value=' + pedido[\"NumeroPedido\"] + '></input>' 
      tabla += '<input name=\'CodigoCliente[]\' type=\'hidden\' value=' + pedido[\"CodigoCliente\"] + '></input>'
      tabla += '<button>Añadir Boceto<ion-icon name=\'create\'></button>'
      tabla += '</form>'
      tabla += '<form action=\'formañadirpdf.php\' method=\'post\'>' 
      tabla += '<input name=\'ejercicio_pedido[]\' type=\'hidden\' value=' + pedido[\"EjercicioPedido\"] + '></input>' 
      tabla += '<input name=\'serie_pedido[]\' type=\'hidden\' value=' + pedido[\"SeriePedido\"] + '></input>' 
      tabla += '<input name=\'numero_pedido[]\' type=\'hidden\' value=' + pedido[\"NumeroPedido\"] + '></input>' 
      tabla += '<input name=\'CodigoCliente[]\' type=\'hidden\' value=' + pedido[\"CodigoCliente\"] + '></input>' 
      tabla += '<button>Añadir Orden Trabajo<ion-icon name=\'create\'></button>' 
      tabla += '</form>'
      tabla += '</div></td>'
      tabla += '<td>'
      tabla += '<img src=\'../../frontend/img/' + pedido[\"Estado\"] + '.png\'/>'
    }
    tabla += '</table>'
    tabla = elementFromHtml(tabla)
    var divTabla = document.getElementById('divTabla');
    if(document.getElementById('tablaClientes') != null){
      divTabla.removeChild(document.getElementById('tablaClientes'));
    }
    divTabla.appendChild(tabla);
  }
  </script>
  ";

  echo "
  <h1>PEDIDOS DE VENTA</h1>
  <div id='divInputs'>
    <label>Ejercicio<input type='text' id='filtro_ejercicio' onchange='filtrar()'></label>
    <label>Serie<input type='text' id='filtro_serie' onchange='filtrar()'></label>
    <label>Número<input type='text' id='filtro_numero' onchange='filtrar()'></label>
    <div class='boton-de-pega'>Buscar</div>
  </div>
  <div id='divTabla'>
  <form id='inputsOcultos' method='post' action='datospedido.php'>
    <input type='hidden' name='IdDelegacion' id='IdDelegacion' value=''/>
    <input type='hidden' name='EjercicioPedido' id='EjercicioPedido' value=''/>
    <input type='hidden' name='SeriePedido' id='SeriePedido' value=''/>
    <input type='hidden' name='NumeroPedido' id='NumeroPedido' value=''/>
    <input type='hidden' name='CodigoCliente' id='CodigoCliente' value=''/>
    <input type='hidden' name='RazonSocial' id='RazonSocial' value=''/>
    <input type='hidden' name='Estado' id='Estado' value=''/>
  </form>"
  ?>
  <?php include "./menuPedidos.php" ?>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>