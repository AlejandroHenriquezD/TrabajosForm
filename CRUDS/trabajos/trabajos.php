<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trabajos Serigrafía</title>
  <link rel="shortcut icon" href="../../frontend/img/favicon.png">
  <link rel="stylesheet" href="../cruds.css">
</head>

<body onload='filtrar()'>
  <?php

  $trabajos = json_decode(file_get_contents("http://localhost/trabajosform/trabajos"), true);

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
  } else {
    $pedidos = json_decode(file_get_contents("http://localhost/centraluniformes/BDReal/json/json_pedidos.php"), true);
  }

  $posiciones = array();
  $tipos_trabajo = array();
  $logos = array();
  $bocetos = array();


  for ($p = 0; $p < count($trabajos); $p++) {
    $posiciones[$p] = array();
    $posiciones[$p] = json_decode(file_get_contents("http://localhost/trabajosform/posiciones/" . $trabajos[$p]['id_posicion']), true);
    $tipos_trabajo[$p] = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos/" . $trabajos[$p]['id_tipo_trabajo']), true);
    $logos[$p] = json_decode(file_get_contents("http://localhost/trabajosform/logos/" . $trabajos[$p]['id_logo']), true);
    $bocetos[$p] = json_decode(file_get_contents("http://localhost/trabajosform/bocetos/" . $trabajos[$p]['id_boceto']), true);
  }

  $trabajos = json_encode($trabajos);
  $posiciones = json_encode($posiciones);
  $tipos_trabajo = json_encode($tipos_trabajo);
  $logos = json_encode($logos);
  $bocetos = json_encode($bocetos);

  echo "
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  function filtrar() {
    var trabajos = $trabajos
    var posiciones = $posiciones
    var tipos_trabajo = $tipos_trabajo
    var logos = $logos
    var bocetos = $bocetos
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
    var tabla = '<table id=\"tablaTrabajos\"><tr><th>Tienda</th><th>Número pedido venta</th><th>Fecha Pedido</th><th>Posición</th><th>Código artículo</th><th>Tipo trabajo</th><th>Logo</th><th>Boceto</th><th>Pdf</th></tr>';
    tabla += '<tr class=\'fila\'>'

    var countTrabajos = 0;
    for(var p=0; p<trabajos.length; p++) {
      if (trabajos[p]['id_logo'] == null) {
        var colLogo = \"No hay logo\";
      } else {
        var colLogo = \"<img src='../.\" + logos[p]['img'] + \"' alt='\" + logos[p]['img'] + \"' height=150px>\";
      }
      var mismoTrabajo = false;
      if(countTrabajos == 0) {
        for(trabajo of trabajos) {
          if (
            trabajo['ejercicio_pedido'] === trabajos[p]['ejercicio_pedido'] &&
            trabajo['serie_pedido'] === trabajos[p]['serie_pedido'] &&
            trabajo['numero_pedido'] === trabajos[p]['numero_pedido']
          ) {
            countTrabajos++;
          }
        }
        tabla += '<td rowspan=\"' + countTrabajos + '\">' + trabajos[p][\"num_tienda\"] + '</td>'
        tabla += '<td rowspan=\"' + countTrabajos + '\">' + trabajos[p][\"ejercicio_pedido\"] + '/' + trabajos[p][\"serie_pedido\"] + '/' + trabajos[p][\"numero_pedido\"] + '</td>'
        tabla += '<td rowspan=\"' + countTrabajos + '\">' + trabajos[p][\"FechaPedido\"] + '</td>'
        mismoTrabajo = true;
      }
      
      tabla += '<td>' + posiciones[p][\"descripcion\"] + '</td>'
      tabla += '<td>' + trabajos[p][\"codigo_articulo\"] + '</td>'
      tabla += '<td>' + tipos_trabajo[p][\"nombre\"] + '</td>'
      tabla += '<td>' + colLogo + '</td>'

      if(mismoTrabajo == true) {
        tabla += '<td rowspan=\"' + countTrabajos + '\">'
        if (trabajos[p]['id_boceto'] != null) {
          tabla += '<form action=\'../.\" + bocetos[p][\'pdf\'] + \"\'><button>Ver Boceto </button></form>'
        } else {
          tabla += 'No Existe Boceto'
        }
        tabla += '</td>'
                
        tabla += '<td rowspan=\"' + countTrabajos + '\">'

        if (trabajos[p]['pdf'] != null) {
          tabla += '<form action=\'../.\" + trabajos[p][\'pdf\'] + \"\'><button>Ver Orden Trabajo</button></form>'
        } else {
          tabla += 'Falta Orden Trabajo'
        }
        tabla += '</td>'
      }
      tabla += '</tr>'
      countTrabajos--;
    }
    tabla += '</table>'
    tabla = elementFromHtml(tabla)
    var divTabla = document.getElementById('divTabla');
    if(document.getElementById('tablaTrabajos') != null){
      divTabla.removeChild(document.getElementById('tablaTrabajos'));
    }
    divTabla.appendChild(tabla);
  }
  </script>
  ";

  echo "<h1>Ordenes de trabajo Serigrafía</h1>
      <div id='divInputs'>
      <label>Ejercicio<input type='text' id='filtro_ejercicio' onchange='filtrar()'></label>
      <label>Serie<input type='text' id='filtro_serie' onchange='filtrar()'></label>
      <label>Número<input type='text' id='filtro_num' onchange='filtrar()'></label>
      <div class='boton-de-pega'>Buscar</div>
      </div>";
  echo "
    <div id='divTabla'></div>
  ";

  ?>
  <?php include "./menuTrabajos.php" ?>

</body>

</html>