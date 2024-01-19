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
  <link rel="stylesheet" href="../cruds.css">
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>

<body onload='filtrar()'>
  <?php

  // Obtener trabajos
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

  $sql = "SELECT DISTINCT num_tienda, ejercicio_pedido, serie_pedido, numero_pedido, id_boceto, pdf, pdf_firmado, FechaPedido, fecha_inicio, fecha_terminado FROM `trabajos`";

  $result = mysqli_query($conn, $sql);
  $trabajos = [];

  while ($trabajo = mysqli_fetch_assoc($result)) {
    $trabajos[] = $trabajo;
  }

  // Obtener pedidos
  include "../../BDReal/numTienda.php";

  $serverName = "192.168.0.23\SQLEXIT,1433";
  $connectionOptions = array(
    "Database" => "ExitERP0415",
    "Uid" => "programacion",
    "PWD" => "CU_2023",
    "CharacterSet" => "UTF-8",
    "TrustServerCertificate" => true
  );
  $connSQLSERVER = sqlsrv_connect($serverName, $connectionOptions);

  $trabajos_pendientes = array();
  $trabajos_todos = array();
  $pedido = array();
  if (isset($_SESSION['usuario'])) {
    foreach ($trabajos as $trabajo) {
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
            WHERE EX_Serigrafiado = -1 
            AND EjercicioPedido = " . $trabajo['ejercicio_pedido'] . " 
            AND SeriePedido = '" . $trabajo['serie_pedido'] . "'
            AND NumeroPedido = " . $trabajo['numero_pedido'] . "
        ";

      $getResults = sqlsrv_query($connSQLSERVER, $sql, array(), array("Scrollable" => 'static'));
      $row_count = sqlsrv_num_rows($getResults);
      if ($row_count > 0) {
        while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
          $pedido = $row;
        }

        $datosPedidos = array(
          'CodigoCliente' => $pedido['CodigoCliente'],
          'CifDni' => $pedido['CifDni'],
          'RazonSocial' => $pedido['RazonSocial']
        );
        $trabajo = array_merge($trabajo, $datosPedidos);

        $sqlMercancia = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0
        AND PVC.IdDelegacion = '" . $pedido['IdDelegacion'] . "' 
        AND PVC.EjercicioPedido = '" . $pedido['EjercicioPedido'] . "'
        AND PVC.SeriePedido = '" . $pedido["SeriePedido"] . "'
        AND PVC.NumeroPedido = '" . $pedido["NumeroPedido"] . "'";

        $getResults = sqlsrv_query($connSQLSERVER, $sqlMercancia, array(), array("Scrollable" => 'static'));

        $row_count = sqlsrv_num_rows($getResults);

        $estado = array();
        if (mysqli_num_rows($result) > 0) {
          if ($trabajo['id_boceto'] != "") {

            //Hacemos la query del boceto para saber si esta firmado o no
            $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $trabajo['id_boceto'];
            $resultBoceto = mysqli_query($conn, $sqlBoceto);
            $rowBoceto = mysqli_fetch_array($resultBoceto);

            // Si esta firmado y el pdf no es null estado correcto
            if ($rowBoceto["firmado"] == 1 && $trabajo["pdf"] != "" && $trabajo["pdf_firmado"] == 1 && $row_count > 0) {
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
        $trabajo = array_merge($trabajo, $estado);

        $sql = "SELECT *
        FROM `chats` 
        WHERE leido = '0'
        AND emisor = 'tienda'
        AND ejercicio_pedido = '" . $pedido['EjercicioPedido'] . "' 
        AND serie_pedido = '" . $pedido["SeriePedido"] . "' 
        AND numero_pedido ='" . $pedido["NumeroPedido"] . "'
        ";
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
          $mensaje = array('Mensaje' => true);
        } else {
          $mensaje = array('Mensaje' => false);
        }
        $trabajo = array_merge($trabajo, $mensaje);

        if ($pedido['StatusPedido'] == "P" && $trabajo['Estado'] == "aceptar") {
          array_push($trabajos_pendientes, $trabajo);
        }
        array_push($trabajos_todos, $trabajo);
      } else {
        unset($trabajo);
      }
    }
  } else {
    $trabajosTemp = array();
    foreach ($trabajos as $trabajo) {
      if ($trabajo['num_tienda'] == $tienda) {
        array_push($trabajosTemp, $trabajo);
      }
    }
    $trabajos = $trabajosTemp;

    foreach ($trabajos as $trabajo) {
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
            WHERE EX_Serigrafiado = -1 
            AND IdDelegacion = '" . $tienda . "' 
            AND EjercicioPedido = " . $trabajo['ejercicio_pedido'] . " 
            AND SeriePedido = '" . $trabajo['serie_pedido'] . "'
            AND NumeroPedido = '" . $trabajo['numero_pedido'] . "'
        ";
      $getResults = sqlsrv_query($connSQLSERVER, $sql, array(), array("Scrollable" => 'static'));
      $row_count = sqlsrv_num_rows($getResults);
      if ($row_count > 0) {
        while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
          $pedido = $row;
        }

        $datosPedidos = array(
          'CodigoCliente' => $pedido['CodigoCliente'],
          'CifDni' => $pedido['CifDni'],
          'RazonSocial' => $pedido['RazonSocial']
        );
        $trabajo = array_merge($trabajo, $datosPedidos);

        $sqlMercancia = "SELECT PVC.EjercicioPedido, PVC.SeriePedido, PVC.NumeroPedido FROM PedidoVentaCabecera AS PVC LEFT JOIN PedidoIntercambioCabecera AS PIC ON PIC.CodigoEmpresa = PVC.CodigoEmpresa AND PIC.EjercicioPedido = PVC.EjercicioPedido AND PIC.SeriePedido = PVC.EX_SeriePedidoIntercambio AND PIC.NumeroPedido = PVC.EX_NumeroPedidoIntercambio WHERE PVC.StatusPedido = 'P' AND PIC.StatusPedido = 'S' AND PIC.AlmacenContrapartida = '55' AND PVC.EX_Serigrafiado = -1 AND PIC.UnidadesPendientes = 0
        AND PVC.IdDelegacion = '" . $pedido['IdDelegacion'] . "' 
        AND PVC.EjercicioPedido = '" . $pedido['EjercicioPedido'] . "'
        AND PVC.SeriePedido = '" . $pedido["SeriePedido"] . "'
        AND PVC.NumeroPedido = '" . $pedido["NumeroPedido"] . "'";

        $getResults = sqlsrv_query($connSQLSERVER, $sqlMercancia, array(), array("Scrollable" => 'static'));

        $row_count = sqlsrv_num_rows($getResults);

        $estado = array();
        if (mysqli_num_rows($result) > 0) {
          if ($trabajo['id_boceto'] != "") {

            //Hacemos la query del boceto para saber si esta firmado o no
            $sqlBoceto = "SELECT firmado FROM `bocetos` WHERE id =" . $trabajo['id_boceto'];
            $resultBoceto = mysqli_query($conn, $sqlBoceto);
            $rowBoceto = mysqli_fetch_array($resultBoceto);

            // Si esta firmado y el pdf no es null estado correcto
            if ($rowBoceto["firmado"] == 1 && $trabajo["pdf"] != "" && $trabajo["pdf_firmado"] == 1 && $row_count > 0) {
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
        $trabajo = array_merge($trabajo, $estado);

        $sql = "SELECT *
        FROM `chats` 
        WHERE leido = '0'
        AND emisor = 'serigrafia'
        AND ejercicio_pedido = '" . $pedido['EjercicioPedido'] . "' 
        AND serie_pedido = '" . $pedido["SeriePedido"] . "' 
        AND numero_pedido ='" . $pedido["NumeroPedido"] . "'
        ";
        
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
          $mensaje = array('Mensaje' => true);
        } else {
          $mensaje = array('Mensaje' => false);
        }
        $trabajo = array_merge($trabajo, $mensaje);

        if ($pedido['StatusPedido'] == 'P') {
          array_push($trabajos_pendientes, $trabajo);
        }
        array_push($trabajos_todos, $trabajo);
      } else {
        unset($trabajo);
      }
    }
  }

  // $bocetos = array();

  // for ($p = 0; $p < count($trabajos); $p++) {
  //   $bocetos[$p] = json_decode(file_get_contents("http://localhost/trabajosform/bocetos/" . $trabajos[$p]['id_boceto']), true);
  // }

  $trabajos = json_encode($trabajos_todos);
  $trabajos_pendientes = json_encode($trabajos_pendientes);
  // $bocetos = json_encode($bocetos);

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
      var trabajos_pendientes = $trabajos_pendientes
 

      if(todos == false) {
        trabajos = $trabajos_pendientes
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
        trabajos = trabajos.filter((trabajos) => trabajos.CodigoCliente.toString().includes(codigo.toString()));
      }
      if(document.getElementById('filtro_cif_nif').value != ''){
        var cif_nif = document.getElementById('filtro_cif_nif').value
        trabajos = trabajos.filter((trabajos) => trabajos.CifDni.includes(cif_nif));
      }

    var tabla = '<table id=\"tablaTrabajos\"><tr><th>Fecha inicio</th><th>Tienda</th><th>Número pedido venta</th><th>Fecha Pedido</th><th>Boceto</th><th>Orden de Trabajo</th><th>Acciones</th><th>Fecha fin</th>'
    ";
  if (isset($_SESSION['usuario'])) {
    echo "
      if(todos == true) {
        tabla += '<th>Estado</th>'
      }
      ";
  }
  echo "
    tabla += '</tr>'
    for(var p=0; p<trabajos.length; p++) {

      tabla += '<tr class=\"fila\">'
      if (trabajos[p]['id_logo'] == null) {
        logoHTML = \"No hay logo\";
      } else {
        logoHTML = \"<img src='../.\" + logos[p]['img'] + \"' alt='\" + logos[p]['img'] + \"' height='150px'>\";
      }
      ";

  if (isset($_SESSION['usuario'])) {
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
        tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + trabajos[p][\"CodigoCliente\"] + '\',\'' + trabajos[p][\"RazonSocial\"] + '\',\'' + trabajos[p][\"Estado\"] + '\')\">' + fechaInicio + '</td>'
        ";
  }
  echo " 
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + trabajos[p][\"CodigoCliente\"] + '\',\'' + trabajos[p][\"RazonSocial\"] + '\',\'' + trabajos[p][\"Estado\"] + '\')\">' + trabajos[p][\"num_tienda\"] + '</td>'
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + trabajos[p][\"CodigoCliente\"] + '\',\'' + trabajos[p][\"RazonSocial\"] + '\',\'' + trabajos[p][\"Estado\"] + '\')\">' + trabajos[p][\"ejercicio_pedido\"] + '/' + trabajos[p][\"serie_pedido\"] + '/' + trabajos[p][\"numero_pedido\"] + '</td>'
      tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + trabajos[p][\"CodigoCliente\"] + '\',\'' + trabajos[p][\"RazonSocial\"] + '\',\'' + trabajos[p][\"Estado\"] + '\')\">' + trabajos[p][\"FechaPedido\"] + '</td>'

      tabla += '<td>'
      if (trabajos[p]['id_boceto'] != null) {

        
        tabla += '<form action=\"../.' + trabajos[p][\"id_boceto\"] + '\" target=\"_blank\"><button>Ver Boceto </button></form>'
      } else {
        tabla += 'No Existe Boceto'
      }
      tabla += '</td>'
              
      tabla += '<td>'

      if (trabajos[p]['pdf'] != null) {
        tabla += '<form action=\"../.'  + trabajos[p][\"pdf\"] + '\" target=\"_blank\"><button>Ver Orden Trabajo</button></form>'
      } else {
        tabla += 'Falta Orden Trabajo'
      }
      tabla += '</td>'
      tabla += '<td>'
      if(trabajos[p][\"Estado\"] == 'cancelar'){
        tabla += '<button onclick=\"confirmarBorrar(\'form-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\')\">Borrar trabajo<ion-icon name=\'trash\'></button>' 
        tabla += '<form id=\"form-' + trabajos[p][\"ejercicio_pedido\"] + '-' + trabajos[p][\"serie_pedido\"] + '-' + trabajos[p][\"numero_pedido\"] + '\" action=\'deletetrabajo.php\' method=\'get\'>' 
        tabla += '<input name=\'ejercicio_pedido\' type=\'hidden\' value=' + trabajos[p][\"ejercicio_pedido\"] + '></input>' 
        tabla += '<input name=\'serie_pedido\' type=\'hidden\' value=' + trabajos[p][\"serie_pedido\"] + '></input>' 
        tabla += '<input name=\'numero_pedido\' type=\'hidden\' value=' + trabajos[p][\"numero_pedido\"] + '></input>' 
        tabla += '</form>'

      } else {
        tabla += '<p>Trabajo en proceso</p>' 
      }
      tabla += '<form action=\'../../frontend/pdf.php\' method=\'get\'>' 
      tabla += '<input name=\'ejercicio_pedido\' type=\'hidden\' value=' + trabajos[p][\"ejercicio_pedido\"] + '></input>' 
      tabla += '<input name=\'serie_pedido\' type=\'hidden\' value=' + trabajos[p][\"serie_pedido\"] + '></input>' 
      tabla += '<input name=\'numero_pedido\' type=\'hidden\' value=' + trabajos[p][\"numero_pedido\"] + '></input>' 
      tabla += '<button>Generar Orden Trabajo</button>' 
      tabla += '</form>'
      ";

  if (isset($_SESSION['usuario'])) {
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
        if(todos == true) {
          tabla += '<td><img src=\'../../frontend/img/' + trabajos[p][\"Estado\"] + '.png\'/></td>'
        }
        ";
  } else {
    echo "
        var fechaFin = (trabajos[p][\"fecha_terminado\"] != null ? trabajos[p][\"fecha_terminado\"] : 'Fecha no asignada');
        fechaFin = fechaFin.replaceAll('-', '/');
        tabla += '<td onclick=\"datosPedido(\'' + trabajos[p][\"num_tienda\"] + '\',\'' + trabajos[p][\"ejercicio_pedido\"] + '\',\'' + trabajos[p][\"serie_pedido\"] + '\',\'' + trabajos[p][\"numero_pedido\"] + '\',\'' + trabajos[p][\"CodigoCliente\"] + '\',\'' + trabajos[p][\"RazonSocial\"] + '\',\'' + trabajos[p][\"Estado\"] + '\')\">' + fechaFin + '</td>'";
  }
  echo "
      if(trabajos[p]['Mensaje'] == true) {
        tabla += '<td class=\"td-mensaje\"><div><ion-icon name=\"mail-outline\"></div></ion-icon></td>'
      }
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

  function confirmarBorrar(formulario) {
    var cuadro = \"<div id='fondo-confirmar-accion'>\"
    cuadro += \"<div id='cuadro-confirmar-accion'>\"
    cuadro += \"<p>¿Está seguro de que quiere borrar esta orden de trabajo?</p>\"
    cuadro += \"<div id='botones-cuadro'><button class='boton-rojo' onclick='cancelarBorrar()'>Cancelar</button>\"
    cuadro += '<button onclick=\"eliminarTrabajo(\'' + formulario + '\')\">Eliminar<ion-icon name=\'trash\'></button></div>'
    cuadro += \"</form>\"
    cuadro += \"</div>\"
    cuadro += \"</div>\"
    cuadro = elementFromHtml(cuadro);
    document.body.appendChild(cuadro);
  }

  function cancelarBorrar() {
    document.body.removeChild(document.getElementById('fondo-confirmar-accion'));
  }

  function eliminarTrabajo(formulario) {
    document.getElementById(formulario).submit();
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