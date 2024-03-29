<?php 
session_start(); 
$_SESSION['VolverDatosPedidos'] = './pedidos.php'; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pedidos de Venta</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body onload='filtrar();'>

  <?php
  include_once "../../BDReal/numTienda.php";

  $serverName = "192.168.0.23\SQLEXIT,1433";
  $connectionOptions = array(
    "Database" => "ExitERP0415",
    "Uid" => "programacion",
    "PWD" => "CU_2023",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
  );
  $connSQLSERVER = sqlsrv_connect($serverName, $connectionOptions);
  if (isset($_SESSION['usuario'])) {
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
              WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 
              ORDER BY EjercicioPedido DESC, SeriePedido ASC, NumeroPedido ASC
          ";
  } else {
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
              WHERE StatusPedido = 'P' AND EX_Serigrafiado = -1 AND IdDelegacion = '" . $tienda . "' 
              ORDER BY EjercicioPedido DESC, SeriePedido ASC, NumeroPedido ASC
          ";
  }
  
  $getResults = sqlsrv_query($connSQLSERVER, $sql);
  if (mysqli_connect_errno()) {
    die("Connection error: " . mysqli_connect_errno());
  }
  
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
  
  $pedidos = [];
  
  while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    $pedidos[] = $row;
    $numero_cliente = $row["CodigoCliente"];
    $cif_nif = $row["CifDni"];
  
    // Compruebo si los datos están en la otra base de datos
    $condicion = mysqli_query($conn, "
                  SELECT 
                      1
                  FROM clientes WHERE 
                      razon_social = '" . $row["RazonSocial"] . "' AND
                      numero_cliente = '" . $row["CodigoCliente"] . "' AND
                      cif_nif = '" . $row["CifDni"] . "'
              ");
  
    // Si no están los introduzco
    if (mysqli_num_rows($condicion) == 0) {
      $sql2 = "
                      INSERT INTO clientes (
                          numero_cliente,
                          cif_nif,
                          razon_social,
                          nombre,
                          dirección,
                          correo,
                          telefono) 
                      VALUES (?,?,?,?,?,?,?)
                  ";
  
      $stmt = mysqli_stmt_init($conn);
  
      if (!mysqli_stmt_prepare($stmt, $sql2)) {
        die(mysqli_errno($conn));
      }
  
      mysqli_stmt_bind_param(
        $stmt,
        "sssssss",
        $row["CodigoCliente"],
        $row["CifDni"],
        $row["RazonSocial"],
        $row["Nombre"],
        $row["Domicilio"],
        $row["Email1"],
        $row["Telefono"]
      );
  
      mysqli_stmt_execute($stmt);
    }
  }
  sqlsrv_free_stmt($getResults);

  for ($i = 0; $i < count($pedidos); $i++) {
    $sqlMercancia = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0
    AND PVC.IdDelegacion = '" . $pedidos[$i]['IdDelegacion'] . "' 
    AND PVC.EjercicioPedido = '" . $pedidos[$i]['EjercicioPedido'] . "'
    AND PVC.SeriePedido = '" . $pedidos[$i]["SeriePedido"] . "'
    AND PVC.NumeroPedido = '" . $pedidos[$i]["NumeroPedido"] . "'";

    $getResults = sqlsrv_query($connSQLSERVER, $sqlMercancia,array(), array( "Scrollable" => 'static' ));

    $row_count = sqlsrv_num_rows($getResults);


    $sql = "SELECT id_boceto,pdf,pdf_firmado FROM trabajos WHERE ejercicio_pedido = '" . $pedidos[$i]['EjercicioPedido'] . "' AND serie_pedido = '" . $pedidos[$i]["SeriePedido"] . "' AND numero_pedido ='" . $pedidos[$i]["NumeroPedido"] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $estado = array();
    $mensajesError = array();
    $errores = "";
    if (mysqli_num_rows($result) > 0) {
      $estado = array('Estado' => "aceptar");
      if ($row[0] == "") {


        $estado = array('Estado' => "cancelar");
        $errores .= "El Boceto No Existe-";
        // $mensajesError[] = array('MensajeError' => "El Boceto No Existe");


      }

      if ($row[0] != "") {

        //Hacemos la query del boceto para saber si esta firmado o no
        // $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $row[0];
        // $resultBoceto = mysqli_query($conn, $sqlBoceto);
        // $rowBoceto = mysqli_fetch_array($resultBoceto);

        // if ($rowBoceto["firmado"] == 0) {
        //   $estado = array('Estado' => "cancelar");
        //   $errores .= "El Boceto No esta firmado-";
        //   // $mensajesError[] = array('MensajeError' => "El Boceto No esta firmado");

        // }
        
      }

      


      if ($row["pdf"] != "") {

        if ($row["pdf_firmado"] == 0) {
          $estado = array('Estado' => "cancelar");
          $errores .= "La Orden de Trabajo no esta firmada-";
          // $mensajesError[] = array("MensajeError" => "La Orden de Trabajo no esta firmada");
        }
      }

      if ($row["pdf"] == "") {
        $estado = array('Estado' => "cancelar");
        $errores .= "La Orden de Trabajo no existe-";
        // $mensajesError[] = array("MensajeError" => "La Orden de Trabajo no existe");

      }

      if ($row_count == 0) {
        $estado = array('Estado' => "cancelar");
        $errores .= "La Mercancia no esta servida en Serigrafía-";
        // $mensajesError[] = array("MensajeError" => "La Mercancia no esta servida en Serigrafía");
      }
    } else {
      $estado = array('Estado' => "cancelar");
      $errores .= "No hay trabajos de este pedido";
      // $mensajesError[] = array("MensajeError" => "No hay trabajos de este pedido");

    }

    $mensajesError = array("MensajeError" => $errores);
    // FALTA LAS CONDICIONES PARA EN LAS QUE NO ESTA LISTA LA DOCUMENTACION Y AÑADIR SI LA MERCANCIA ESTA SERVIDA O NO
    // echo json_encode($mensajesError);

    $pedidos[$i] = array_merge($pedidos[$i], $estado);
    $pedidos[$i] = array_merge($pedidos[$i], $mensajesError);


    // echo json_encode($pedidos[$i]);
    if (isset($_SESSION['usuario'])) {
      $sql = "SELECT *
      FROM `chats` 
      WHERE leido = '0'
      AND emisor = 'tienda'
      AND ejercicio_pedido = '" . $pedidos[$i]['EjercicioPedido'] . "' 
      AND serie_pedido = '" . $pedidos[$i]["SeriePedido"] . "' 
      AND numero_pedido ='" . $pedidos[$i]["NumeroPedido"] . "'
      ";
    } else {
      $sql = "SELECT *
      FROM `chats` 
      WHERE leido = '0'
      AND emisor = 'serigrafia'
      AND ejercicio_pedido = '" . $pedidos[$i]['EjercicioPedido'] . "' 
      AND serie_pedido = '" . $pedidos[$i]["SeriePedido"] . "' 
      AND numero_pedido ='" . $pedidos[$i]["NumeroPedido"] . "'
      ";
    }
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $mensaje = array('Mensaje' => true);
    } else {
      $mensaje = array('Mensaje' => false);
    }
    $pedidos[$i] = array_merge($pedidos[$i], $mensaje);
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
      tabla += '<td><div class=\"estado-pedido\">'
      tabla += '<img src=\'../../frontend/img/' + pedido[\"Estado\"] + '.png\'/>'

      if(pedido['Estado'] != 'aceptar'){
        var mensajes = pedido['MensajeError'].split('-');
        for(mensaje of mensajes){
          tabla += mensaje + '<br>'
        }
      }
      tabla += '</div></td>'
      if(pedido['Mensaje'] == true) {
        tabla += '<td class=\"td-mensaje\"><div><ion-icon name=\"mail-outline\"></div></ion-icon></td>'
      }
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