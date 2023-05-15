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

<body onload="filtrar()">

  <?php

  $clientes = json_encode(file_get_contents("http://localhost/trabajosform/clientes"), true);

  echo "
  <script>

    function elementFromHtml(html) {
      const template = document.createElement('template');

      template.innerHTML = html.trim();

      return template.content.firstElementChild;
    }

    function datosCliente(cliente) {
      document.getElementById('nombre').value = cliente[\"nombre\"];
      document.getElementById('telefono').value = cliente[\"telefono\"];
      document.getElementById('correo').value = cliente[\"correo\"];
      document.getElementById('dirección').value = cliente[\"dirección\"];
      document.getElementById('cif_nif').value = cliente[\"cif_nif\"];
      document.getElementById('numero_cliente').value = cliente[\"numero_cliente\"];
      document.getElementById('razon_social').value = cliente[\"razon_social\"];
      console.log(document.getElementById('nombre').value)
      // document.getElementById('inputsOcultos').submit();
    }

    function filtrar() {
      var clientes = $clientes
      clientes = JSON.parse(clientes);
      if(document.getElementById('filtro_razon').value != ''){
        var razon_social = document.getElementById('filtro_razon').value
        clientes = clientes.filter((clientes) => clientes.razon_social.toUpperCase().includes(razon_social.toUpperCase()));
      }
      if(document.getElementById('filtro_codigo').value != ''){
        var codigo = document.getElementById('filtro_codigo').value
        clientes = clientes.filter((clientes) => clientes.numero_cliente.includes(codigo));
      }
      if(document.getElementById('filtro_nif').value != ''){
        var CIF_NIF = document.getElementById('filtro_nif').value
        clientes = clientes.filter((clientes) => clientes.cif_nif.toUpperCase().includes(CIF_NIF.toUpperCase()));
      }
      console.log(clientes)
      var tabla = '<table id=\"tablaClientes\"><tr><th>Nombre</th><th>Telefono</th><th>Correo</th><th>Dirección</th><th>CIF/NIF</th><th>Código de cliente</th><th>Razón social</th></tr>';
      for(cliente of clientes) {
        tabla += '<tr class=\"fila\" onclick=datosCliente(cliente)>'
        tabla += '<td>' + cliente[\"nombre\"] + '</td>'
        tabla += '<td>' + cliente[\"telefono\"] + '</td>'
        tabla += '<td>' + cliente[\"correo\"] + '</td>'
        tabla += '<td>' + cliente[\"dirección\"] + '</td>'
        tabla += '<td>' + cliente[\"cif_nif\"] + '</td>'
        tabla += '<td>' + cliente[\"numero_cliente\"] + '</td>'
        tabla += '<td>' + cliente[\"razon_social\"] + '</td>'
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

  $clientes = json_decode(file_get_contents("http://localhost/trabajosform/clientes"), true);

  echo "
  <h1>CLIENTES</h1>
  <div id='divInputs'>
    <label>Razón social<input type='text' id='filtro_razon' onchange='filtrar()'></label>
    <label>Código de cliente<input type='text' id='filtro_codigo' onchange='filtrar()'></label>
    <label>CIF/NIF<input type='text' id='filtro_nif' onchange='filtrar()'></label>
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
  <?php include "./menuCliente.php" ?>
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>