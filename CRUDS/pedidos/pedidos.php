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


  if (isset($_SESSION['usuario'])) {
    $pedidos = json_encode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos_todos.php"), true);
  } else {
    $pedidos = json_encode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
  }

  echo "
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  function filtrar() {
    var pedidos = $pedidos
    pedidos = JSON.parse(pedidos);
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
    console.log(pedidos)
    var tabla = '<table id=\"tablaClientes\"><tr><th>Tienda</th><th>Ejercicio</th><th>Serie</th><th>Número</th><th>Código cliente</th><th>Razón social</th><th>Acciones</th><th>Estado</th></tr>';
    for(pedido of pedidos) {
      tabla += '<tr class=\"fila\" onclick=datosPedido(pedido)>'
      tabla += '<td>' + pedido[\"IdDelegacion\"] + '</td>'
      tabla += '<td>' + pedido[\"EjercicioPedido\"] + '</td>'
      tabla += '<td>' + pedido[\"SeriePedido\"] + '</td>'
      tabla += '<td>' + pedido[\"NumeroPedido\"] + '</td>'
      tabla += '<td>' + pedido[\"CodigoCliente\"] + '</td>'
      tabla += '<td>' + pedido[\"RazonSocial\"] + '</td>'
      tabla += '<td>' 
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
      tabla += '</td>'

      tabla += '</tr>';
    }
    tabla += '</table>'
    tabla = elementFromHtml(tabla);
    var divTabla = document.getElementById('divTabla');
    if(document.getElementById('tablaClientes') != null){
      divTabla.removeChild(document.getElementById('tablaClientes'));
    }
    divTabla.appendChild(tabla);
    console.log(tabla);
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
<form id='inputsOcultos' action='datoscliente.php'>
  <input type='text' name='nombre' id='nombre' value=''/>
  <input type='text' name='telefono' id='telefono' value=''/>
  <input type='text' name='correo' id='correo' value=''/>
  <input type='text' name='dirección' id='dirección' value=''/>
  <input type='text' name='cif_nif' id='cif_nif' value=''/>
  <input type='text' name='numero_cliente' id='numero_cliente' value=''/>
  <input type='text' name='razon_social' id='razon_social' value=''/>
</form>"
  ?>
  <?php include "./menuPedidos.php" ?>

  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>