<?php
session_start();
$_SESSION['VolverDatosPedidos'] = '../trabajos/trabajos.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabajos Serigrafía</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds2.css">
</head>

<body onload='filtrar()'>
  <?php

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

  $sql = "SELECT DISTINCT num_tienda, ejercicio_pedido, serie_pedido, numero_pedido, id_boceto, pdf, FechaPedido, fecha_inicio, fecha_terminado FROM `trabajos`";

  $result = mysqli_query($conn, $sql);
  $trabajos = [];

  while ($trabajo = mysqli_fetch_assoc($result)) {
    $trabajos[] = $trabajo;
  }

  if (!isset($_SESSION['usuario'])) {
    include "../../BDReal/numTienda.php";
    $trabajosTemp = array();
    foreach ($trabajos as $trabajo) {
      if ($trabajo['num_tienda'] == $tienda) {
        array_push($trabajosTemp, $trabajo);
      }
    }
    $trabajos = $trabajosTemp;
  }

  if (isset($_SESSION['usuario'])) {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_todos.php"), true);
    $pedidosnopen = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_todos_nopen.php"), true);
  } else {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
    $pedidosnopen = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_nopen.php"), true);
  }

  $bocetos = array();

  for ($p = 0; $p < count($trabajos); $p++) {
    $bocetos[$p] = json_decode(file_get_contents("http://localhost/trabajosform/bocetos/" . $trabajos[$p]['id_boceto']), true);
  }

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
    $pedidosListos[$i] = array();
    $sqlMercancia = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0
    AND PVC.IdDelegacion = '" . $pedidos[$i]['IdDelegacion'] . "' 
    AND PVC.EjercicioPedido = '" . $pedidos[$i]['EjercicioPedido'] . "'
    AND PVC.SeriePedido = '" . $pedidos[$i]["SeriePedido"] . "'
    AND PVC.NumeroPedido = '" . $pedidos[$i]["NumeroPedido"] . "'";

    $getResults = sqlsrv_query($connSQLSERVER, $sqlMercancia, array(), array("Scrollable" => 'static'));

    $row_count = sqlsrv_num_rows($getResults);

    $sql = "SELECT id_boceto,pdf,pdf_firmado FROM trabajos WHERE ejercicio_pedido = '" . $pedidos[$i]['EjercicioPedido'] . "' AND serie_pedido = '" . $pedidos[$i]["SeriePedido"] . "' AND numero_pedido ='" . $pedidos[$i]["NumeroPedido"] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $estado = array();
    if (mysqli_num_rows($result) > 0) {
      if ($row[0] != "") {

        //Hacemos la query del boceto para saber si esta firmado o no
        $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $row[0];
        $resultBoceto = mysqli_query($conn, $sqlBoceto);
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
    $pedidos[$i] = array_merge($pedidos[$i], $estado);
    if($pedidos[$i]['Estado'] == "aceptar") {
      $pedidosListos[$i] = $pedidos[$i];
    }
  }

  $pedidosnopenListos = array();
  for ($i = 0; $i < count($pedidosnopen); $i++) {
    $pedidosnopenListos[$i] = array();

    $sql = "SELECT id_boceto,pdf,pdf_firmado FROM trabajos WHERE ejercicio_pedido = '" . $pedidosnopen[$i]['EjercicioPedido'] . "' AND serie_pedido = '" . $pedidosnopen[$i]["SeriePedido"] . "' AND numero_pedido ='" . $pedidosnopen[$i]["NumeroPedido"] . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $estado = array();
    if (mysqli_num_rows($result) > 0) {
      if ($row[0] != "") {

        //Hacemos la query del boceto para saber si esta firmado o no
        $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $row[0];
        $resultBoceto = mysqli_query($conn, $sqlBoceto);
        $rowBoceto = mysqli_fetch_array($resultBoceto);

        // Si esta firmado y el pdf no es null estado correcto
        if ($rowBoceto["firmado"] == 1 && $row["pdf"] != "" && $row["pdf_firmado"] == 1) {
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
    $pedidosnopen[$i] = array_merge($pedidosnopen[$i], $estado);
    if($pedidosnopen[$i]['Estado'] == "aceptar") {
      $pedidosnopenListos[$i] = $pedidosnopen[$i];
    }
  }

  if(isset($_SESSION['usuario'])) {
    $pedidos = json_encode($pedidosListos);
    $pedidosnopen = json_encode($pedidosnopenListos);
  } else {
    $pedidos = json_encode($pedidos);
    $pedidosnopen = json_encode($pedidosnopen);
  }
  $trabajos = json_encode($trabajos);
  $bocetos = json_encode($bocetos);

  echo "
  <script>
    var todos = false;

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

    function actualizarFecha(tipo) {
      document.getElementById(tipo).submit();
    }

    function filtrar() {
      var trabajos = $trabajos
      var bocetos = $bocetos
      var pedidos = $pedidos

      if(todos == true) {
        var pedidos = $pedidosnopen

      }
      if(document.getElementById('filtro_serie').value != ''){
        var serie = document.getElementById('filtro_serie').value
        trabajos = trabajos.filter((trabajos) => trabajos.serie_pedido.toUpperCase().includes(serie.toUpperCase()));
      }
      if(document.getElementById('filtro_ejercicio').value != ''){
        var ejercicio = document.getElementById('filtro_ejercicio').value
        trabajos = trabajos.filter((trabajos) => trabajos.ejercicio_pedido.toString().includes(ejercicio.toString()));
      }
      if(document.getElementById('filtro_num').value != ''){
        var numero = document.getElementById('filtro_num').value
        trabajos = trabajos.filter((trabajos) => trabajos.numero_pedido.toString().includes(numero.toString()));
      }
      if(document.getElementById('filtro_codigo').value != ''){
        var codigo = document.getElementById('filtro_codigo').value
        pedidos = pedidos.filter((pedidos) => pedidos.CodigoCliente.toString().includes(codigo.toString()));
      }
      if(document.getElementById('filtro_cif_nif').value != ''){
        var cif_nif = document.getElementById('filtro_cif_nif').value
        pedidos = pedidos.filter((pedidos) => pedidos.CifDni.includes(cif_nif));
      }

    trabajostemp = [];
    pedidostemp = [];
    for(pedido of pedidos){
      for(trabajo of trabajos){
        if(pedido.EjercicioPedido == trabajo.ejercicio_pedido && pedido.NumeroPedido == trabajo.numero_pedido && pedido.SeriePedido == trabajo.serie_pedido){
          trabajostemp.push(trabajo);
          pedidostemp.push(pedido);
        }
      }
    }
  
    trabajos = trabajostemp;
    pedidos = pedidostemp;
    
    var tabla = '<table id=\"tablaTrabajos\"><tr><th>Fecha inicio</th><th>Tienda</th><th>Número pedido venta</th><th>Fecha Pedido</th><th>Boceto</th><th>Pdf</th><th>Acciones</th><th>Fecha fin</th></tr>';
    
    for(var p=0; p<trabajos.length; p++) {

      tabla += '<tr class=\"fila\">'
      if (trabajos[p]['id_logo'] == null) {
        logoHTML = \"No hay logo\";
      } else {
        logoHTML = \"<img src='../.\" + logos[p]['img'] + \"' alt='\" + logos[p]['img'] + \"' height='150px'>\";
      }
      ";

      if(isset($_SESSION['usuario'])) {
        echo "
        tabla += '<td><form id=\"fecha-inicio-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\" action=\"updateFecha.php\" method=\"post\"><input name=\"fecha_inicio\" type=\"date\" onchange=actualizarFecha(\"fecha-inicio-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\") value=' + trabajos[p][\"fecha_inicio\"] + '>'
        tabla += '<input type=\"hidden\" name=\"num_tienda\" value=' + trabajos[p][\"num_tienda\"] + '>'
        tabla += '<input type=\"hidden\" name=\"ejercicio_pedido\" value=' + trabajos[p][\"ejercicio_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"serie_pedido\" value=' + trabajos[p][\"serie_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"numero_pedido\" value=' + trabajos[p][\"numero_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"id_boceto\" value=' + trabajos[p][\"id_boceto\"] + '>'
        tabla += '<input type=\"hidden\" name=\"pdf\" value=' + trabajos[p][\"pdf\"] + '>'
        tabla += '<input type=\"hidden\" name=\"FechaPedido\" value=' + trabajos[p][\"FechaPedido\"] + '>'
        tabla += '</form></td>'
        ";
      } else { 
        echo "
        var fechaInicio = (trabajos[p][\"fecha_inicio\"] != null ? trabajos[p][\"fecha_inicio\"] : 'Fecha no asignada');
        fechaInicio = fechaInicio.replaceAll('-', '/');
        tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">' + fechaInicio + '</td>'
        ";
      }
      echo " 
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">' + trabajos[p][\"num_tienda\"] + '</td>'
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">' + trabajos[p][\"ejercicio_pedido\"] + '/' + trabajos[p][\"serie_pedido\"] + '/' + trabajos[p][\"numero_pedido\"] + '</td>'
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">' + trabajos[p][\"FechaPedido\"] + '</td>'

      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">'
      if (trabajos[p]['id_boceto'] != null) {
        console.log(bocetos[p][\"pdf\"]);
        
        tabla += '<form action=\"../.' + bocetos[p][\"pdf\"] + '\"><button>Ver Boceto </button></form>'
      } else {
        tabla += 'No Existe Boceto'
      }
      tabla += '</td>'
              
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">'

      if (trabajos[p]['pdf'] != null) {
        tabla += '<form action=\"../.'  + trabajos[p][\"pdf\"] + '\"><button>Ver Orden Trabajo</button></form>'
      } else {
        tabla += 'Falta Orden Trabajo'
      }
      tabla += '</td>'
      tabla += '<td>'

      if(pedidos[p][\"Estado\"] == 'cancelar'){
        tabla += '<form action=\'deletetrabajo.php\' method=\'get\'>' 
        tabla += '<input name=\'ejercicio_pedido\' type=\'hidden\' value=' + trabajos[p][\"ejercicio_pedido\"] + '></input>' 
        tabla += '<input name=\'serie_pedido\' type=\'hidden\' value=' + trabajos[p][\"serie_pedido\"] + '></input>' 
        tabla += '<input name=\'numero_pedido\' type=\'hidden\' value=' + trabajos[p][\"numero_pedido\"] + '></input>' 
        tabla += '<button>Borrar trabajo<ion-icon name=\'trash\'></button>' 
        tabla += '</form>'
        tabla += '</td>'
      } else{
        tabla += '<p>Trabajo en proceso</p>' 
      }
      tabla += '</td>'
      ";

      if(isset($_SESSION['usuario'])) {
        echo "
        tabla += '<td><form id=\"fecha-terminado-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\" action=\"updateFecha.php\" method=\"post\"><input name=\"fecha_terminado\" type=\"date\" onchange=actualizarFecha(\"fecha-terminado-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\") value=' + trabajos[p][\"fecha_terminado\"] + '>'
        tabla += '<input type=\"hidden\" name=\"num_tienda\" value=' + trabajos[p][\"num_tienda\"] + '>'
        tabla += '<input type=\"hidden\" name=\"ejercicio_pedido\" value=' + trabajos[p][\"ejercicio_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"serie_pedido\" value=' + trabajos[p][\"serie_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"numero_pedido\" value=' + trabajos[p][\"numero_pedido\"] + '>'
        tabla += '<input type=\"hidden\" name=\"id_boceto\" value=' + trabajos[p][\"id_boceto\"] + '>'
        tabla += '<input type=\"hidden\" name=\"pdf\" value=' + trabajos[p][\"pdf\"] + '>'
        tabla += '<input type=\"hidden\" name=\"FechaPedido\" value=' + trabajos[p][\"FechaPedido\"] + '>'
        tabla += '</form></td>'
        ";
      } else {
        echo "
        var fechaFin = (trabajos[p][\"fecha_terminado\"] != null ? trabajos[p][\"fecha_terminado\"] : 'Fecha no asignada');
        fechaFin = fechaFin.replaceAll('-', '/');
        tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + pedidos[p][\"CodigoCliente\"] + '\',\'' + pedidos[p][\"RazonSocial\"] + '\',\'' + pedidos[p][\"Estado\"] + '\')\">' + fechaFin + '</td>'";
      }
      echo "
      tabla += '</tr>'
    }
  
    tabla += '</table>';
    tabla = elementFromHtml(tabla);
  
    var divTabla = document.getElementById('divTabla');
  
    if (document.getElementById('tablaTrabajos') != null) {
      divTabla.removeChild(document.getElementById('tablaTrabajos'));
    }
  
    divTabla.appendChild(tabla);
  }
  

  function mostrarTodos() {
    if(todos == true) {
      todos = false;
      document.getElementById('mostrarTodos').innerHTML = 'Mostrar todos';
    } else {
      todos = true;
      document.getElementById('mostrarTodos').innerHTML = 'Mostrar pendientes';
    }
    filtrar();
  }
  </script>
  ";

  echo "<h1>Ordenes de trabajo Serigrafía</h1>
      <div id='divInputs'>
        <label>Ejercicio<input type='text' id='filtro_ejercicio' onchange='filtrar()'></label>
        <label>Serie<input type='text' id='filtro_serie' onchange='filtrar()'></label>
        <label>Número<input type='text' id='filtro_num' onchange='filtrar()'></label>
        <div class='boton-de-pega'>Buscar</div>
        <label>Número cliente<input type='text' id='filtro_codigo' onchange='filtrar()'></label>
        <label>CIF/NIF<input type='text' id='filtro_cif_nif' onchange='filtrar()'></label>
        <button id='mostrarTodos' class='boton-de-pega' onclick='mostrarTodos()'>Mostrar todos</button>
      </div>
      <form id='inputsOcultos' method='post' action='../pedidos/datospedido.php'>
        <input type='hidden' name='IdDelegacion' id='IdDelegacion' value=''/>
        <input type='hidden' name='EjercicioPedido' id='EjercicioPedido' value=''/>
        <input type='hidden' name='SeriePedido' id='SeriePedido' value=''/>
        <input type='hidden' name='NumeroPedido' id='NumeroPedido' value=''/>
        <input type='hidden' name='CodigoCliente' id='CodigoCliente' value=''/>
        <input type='hidden' name='RazonSocial' id='RazonSocial' value=''/>
        <input type='hidden' name='Estado' id='Estado' value=''/>
      </form>";
  echo "
    <div id='divTabla'></div>
  ";
  if (isset($_SESSION['confirmarAccion'])) {
    include "../confirmarAccion.php";
  }
  ?>
  <?php include "./menuTrabajos.php" ?>

</body>

</html>