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

$pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"), true);
$pedidosArticulos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos_articulos"), true);
$articulos = json_decode(file_get_contents("http://localhost/trabajosform/articulos"), true);
$tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);
$tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);
$tiposPosiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);
$logos = array();
$posicionesArticulos = json_decode(file_get_contents("http://localhost/trabajosform/posiciones_tipo_articulos/"), true);
// $logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
// $logos_encoded = json_encode($logos);
$numeroPedidos = count($pedidos);
$numeroPedidosArticulos = count($pedidosArticulos);
$numeroArticulos = count($articulos);
$numeroTrabajos = count($tiposTrabajos);
$numeroTipoArticulos = count($tiposArticulos);
$numeroPosicionesArticulos = count($posicionesArticulos);
$numeroPosiciones = count($tiposPosiciones);

$arrayBocetos = array();
$bocetosUrl = array();
$arrayArticulos = array();
$trabajos = array();
$arrayTipoArticulos = array();
$arrayLogos = array();
$posiciones = array();
$arrayLogos = array();
$relacion = array();
$desplegablesTipoArticulos = array();
$desplegablesPosiciones = array();
$desplegablesLogos = array();
$divPedidos = "<div id='pedidos'><div id='divPedidos'><h1>Pedido</h1><select name='selectPedido[]' id='selectPedido' onchange=mostrarArticulos(this.value)>";
for ($o = 0; $o < $numeroPedidos; $o++) {
  $divPedidos .= "<option id={$pedidos[$o]['id']} value={$pedidos[$o]['id']}>{$pedidos[$o]['id']}</option>";
}
$divPedidos .= "</select></div>";


for ($o = 0; $o < $numeroPedidos; $o++) {
  $arrayBocetos[$o] = "<div class='boceto' id='boceto-{$pedidos[$o]['id']}'><h1>Boceto</h1><select name='selectBoceto[]' id='selectBoceto' onchange='updatePdf()'>";
  $sql = "SELECT * FROM `bocetos` WHERE id_cliente =" . $pedidos[$o]['id_cliente'];
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $arrayBocetos[$o] .= "<option id=" . trim(json_encode([$row["id"]][0]), '"')  . " value=" . trim(json_encode([$row["id"]][0]), '"') . ">" . trim(json_encode([$row['nombre']][0]), '"') . "</option>";
      $bocetosUrl[$o][$row["id"]][0] = trim(json_encode([$row["pdf"]][0]), '"');
    }
  }
  $arrayBocetos[$o] .= "</select></div>";

  // $logos = array();
  $sql = "SELECT * FROM `logos` WHERE id_cliente =" . $pedidos[$o]['id_cliente'];
  $result = mysqli_query($conn, $sql);
  if (mysqli_num_rows($result) > 0) {
    $l = 0;
    while ($row = mysqli_fetch_assoc($result)) {
      $logos[$o][$l] = $row;
      $l++;
    }
    // $logos = mysqli_fetch_all($result);
    // json_decode(file_get_contents($logos), true);
    // $logos = json_encode($logos);
    // echo $logos;
  } else {
    $logos[$o] = "No hay logos";
  };


  $arrayArticulos[$o] = "<div class='articulo' id='articulos-{$pedidos[$o]['id']}'><h1>Articulos </h1>";
  for ($j = 0; $j < $numeroPedidosArticulos; $j++) {
    if ($pedidosArticulos[$j]['id_pedido'] == $pedidos[$o]['id']) {
      $relacion[$o][$j] = $pedidosArticulos[$j]['id_articulo'];
    } else {
      $relacion[$o][$j] = null;
    }
    for ($i = 0; $i < $numeroArticulos; $i++) {
      if ($relacion[$o][$j] == $articulos[$i]['id']) {
        $arrayArticulos[$o] .= "<div id=\"form-control-{$articulos[$i]['id']}\">";
        $arrayArticulos[$o] .= "<input type='checkbox' id=\"articulo-{$articulos[$i]['id']}\" name='articulo[]' value=\"{$articulos[$i]['descripcion']}\" onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}\")'>";
        $arrayArticulos[$o] .= "<label for=\"articulo-{$articulos[$i]['id']}\">" . $articulos[$i]['descripcion'] . "</label><br>";
        $arrayArticulos[$o] .= "</div>";
      }
      $arrayTipoArticulos[$o][$i] = "<div class='tipoArticulo' id=\"tipoArticulos-{$articulos[$i]['id']}\">";
      $arrayTipoArticulos[$o][$i] .= "<h1>Tipo de articulo</h1><div class='slider'><div class='coleccion'>";
      for ($a = 0; $a < $numeroTipoArticulos; $a++) {
        $arrayTipoArticulos[$o][$i] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\">";
        $arrayTipoArticulos[$o][$i] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\" name=\"articuloRadio-{$articulos[$i]['id']}\" value=\"{$tiposArticulos[$a]['nombre']}\" onclick='mostrarTrabajos(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\")'>";
        $arrayTipoArticulos[$o][$i] .= "<img src=\".{$tiposArticulos[$a]['img']}\" alt=\".{$tiposArticulos[$a]['img']}\"/>";
        $arrayTipoArticulos[$o][$i] .= "<br></div>";
        $trabajos[$o][$i][$a] = "<div class='trabajos' id=\"trabajos-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\"><h1>Trabajos</h1><div class='coleccionHorizontal'>";
        for ($t = 0; $t < $numeroTrabajos; $t++) {
          $trabajos[$o][$i][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\">";
          $trabajos[$o][$i][$a] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
          $trabajos[$o][$i][$a] .= "<label for=\"trabajo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\">" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
          $trabajos[$o][$i][$a] .= "</div>";
          $posiciones[$o][$i][$a][$t] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\"><div class='seleccionado'><h1>{$tiposTrabajos[$t]['nombre']}</h1></div><h1>Posiciones: </h1><div class='coleccionHorizontal'>";
          for ($p = 0; $p < $numeroPosicionesArticulos; $p++) {
            $arrayLogos[$o][$i][$a][$t][$p] = "<div class='logos' id=\"logos-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
            $posIndex = array_search($posicionesArticulos[$p]['id_posicion'], array_column($tiposPosiciones, 'id'));
            if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
              // Obtiene la posición del array donde se encuentra el id de la posición
              $posiciones[$o][$i][$a][$t] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
              $posiciones[$o][$i][$a][$t] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" class='posicion-checkbox' name='posicion-checkbox[]' value=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
              $posiciones[$o][$i][$a][$t] .= "<label for=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">" . $tiposPosiciones[$posIndex]['descripcion'] . "</label><br>";
              $posiciones[$o][$i][$a][$t] .= "</div>";
            }
            // Iba dentro del if de arriba
            $arrayLogos[$o][$i][$a][$t][$p] .= "<div class='seleccionado'><h1>{$tiposPosiciones[$posIndex]['descripcion']}</h1></div><h1>Logotipo</h1><div class='slider'><div class='coleccion'>";
            if ($logos[$o] != "No hay logos") {
              for ($l = 0; $l < count($logos[$o]); $l++) {
                $arrayLogos[$o][$i][$a][$t][$p] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$o][$l]['id']}\">";
                $arrayLogos[$o][$i][$a][$t][$p] .= "<input type='radio' class=\"logoRadio-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" id=\"logo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$o][$l]['id']}\" name=\"img-input[grupo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}]\" value=\"logo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$o][$l]['id']}\" onclick='logoSeleccionado(\"logoRadio-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
                $arrayLogos[$o][$i][$a][$t][$p] .= "<img src=\".{$logos[$o][$l]['img']}\" alt=\".{$logos[$o][$l]['img']}\"/>";
                $arrayLogos[$o][$i][$a][$t][$p] .= "</div>";
              }
            }
            $desplegablesLogos[$o][$i][$a][$t][$p] = "<div class='desplegable' id=\"desplegable-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='desplegable(\"logos-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'><div class='flecha' id=\"flecha-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\"></div></div>";
          }
          $posiciones[$o][$i][$a][$t] .= "</div></div>";
          $desplegablesPosiciones[$o][$i][$a][$t] = "<div class='desplegable' id=\"desplegable-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\" onclick='desplegable(\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\")'><div class='flecha' id=\"flecha-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\"></div></div>";
        }
        $trabajos[$o][$i][$a] .= "</div>";
      }
      $arrayTipoArticulos[$o][$i] .= "</div></div></div>";
      $desplegablesTipoArticulos[$o][$i] = "<div class='desplegable' id=\"desplegable-{$articulos[$i]['id']}\" onclick='desplegable(\"tipoArticulos-{$articulos[$i]['id']}\")'><div class='flecha' id=\"flecha-{$articulos[$i]['id']}\"></div></div>";
    }
  }
  $arrayArticulos[$o] .= "</div>";
}
$divPedidos .= "</div>";

$bocetosUrl = json_encode($bocetosUrl);
$arrayBocetos = json_encode($arrayBocetos);
$arrayArticulos = json_encode($arrayArticulos);
$arrayTipoArticulos = json_encode($arrayTipoArticulos);
$desplegablesTipoArticulos = json_encode($desplegablesTipoArticulos);
$arrayTrabajos = json_encode($trabajos);
$arrayPosiciones = json_encode($posiciones);
$desplegablesPosiciones = json_encode($desplegablesPosiciones);
$arrayLogos = json_encode($arrayLogos);
$desplegablesLogos = json_encode($desplegablesLogos);
$art = json_encode($articulos);
$tra = json_encode($tiposTrabajos);
$tart = json_encode($tiposArticulos);
$pos = json_encode($tiposPosiciones);
$relacion = json_encode($relacion);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Index</title>
  <link rel='shortcut icon' href='favicon.png'>
  <link rel='stylesheet' href='styles.css'>
</head>
<body onload='primeraFuncion();'>
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  var bocetosUrl = $bocetosUrl;
  console.log(bocetosUrl);
  var bocetos = $arrayBocetos;
  var articulos = $arrayArticulos;
  var tipoArticulos = $arrayTipoArticulos;
  var desplegablesTipoArticulos = $desplegablesTipoArticulos;
  var trabajos = $arrayTrabajos;
  var posiciones = $arrayPosiciones;
  var desplegablesPosiciones = $desplegablesPosiciones;
  var logos = $arrayLogos;
  var desplegablesLogos = $desplegablesLogos;
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    bocetos[i] = elementFromHtml(bocetos[i]);
    for (var j = 0; j < tipoArticulos[i].length; j++) {
      tipoArticulos[i][j] = elementFromHtml(tipoArticulos[i][j]);
      desplegablesTipoArticulos[i][j] = elementFromHtml(desplegablesTipoArticulos[i][j]);
      for (var k = 0; k < trabajos[i][j].length; k++) {
        trabajos[i][j][k] = elementFromHtml(trabajos[i][j][k]);
        for (var l = 0; l < posiciones[i][j][k].length; l++) {
          posiciones[i][j][k][l] = elementFromHtml(posiciones[i][j][k][l]);
          desplegablesPosiciones[i][j][k][l] = elementFromHtml(desplegablesPosiciones[i][j][k][l]);
          for (var m = 0; m < logos[i][j][k][l].length; m++) {
            logos[i][j][k][l][m] = elementFromHtml(logos[i][j][k][l][m]);
            desplegablesLogos[i][j][k][l][m] = elementFromHtml(desplegablesLogos[i][j][k][l][m]);
          }
        }
      }
    }
  }

  var elementoActual = null;
  
  function primeraFuncion() {
    document.getElementById('pedidos').appendChild(bocetos[0]);
    document.getElementById('pedidos').appendChild(articulos[0]);
    var select = document.getElementById('selectPedido');
    elementoActual = select.options[select.selectedIndex].value;


    var pdf = '<iframe src=\"\" style=\"width:100%; height:100%;\" frameborder=\"0\"></iframe>'
    document.getElementById('div-pdf').appendChild(elementFromHtml(pdf));
    updatePdf();
    validar();
  }

  function obtenerElemento(array, id) {
    console.log(id);
    var elemento = array.find(a => a.id === id);
    return elemento;
  }

  function indexPedido() {
    var select = document.getElementById('selectPedido');
    return select.selectedIndex;
  }

  function mostrarArticulos(elemento) {
    var boceto = obtenerElemento(bocetos, 'boceto-'+elemento);
    var bocetoAntiguo = obtenerElemento(bocetos, 'boceto-'+elementoActual);
    document.getElementById('pedidos').removeChild(bocetoAntiguo);
    document.getElementById('pedidos').appendChild(boceto);

    var articulo = obtenerElemento(articulos, 'articulos-'+elemento);
    var articuloAntiguo = obtenerElemento(articulos, 'articulos-'+elementoActual);
    document.getElementById('pedidos').removeChild(articuloAntiguo);
    document.getElementById('pedidos').appendChild(articulo);

    elementoActual = elemento;
    updatePdf()
    validar()
  }

  function mostrarTiposArticulos(elemento) {
    var numeroArticulo = elemento.split('-')[2];

    var desplegable = obtenerElemento(desplegablesTipoArticulos[indexPedido()], 'desplegable-'+numeroArticulo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()], 'tipoArticulos-'+numeroArticulo);

    if (document.getElementById('articulo-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(tipoArticulo);
      document.getElementById(elemento).appendChild(desplegable); 
    } else {
      document.getElementById(elemento).removeChild(tipoArticulo);
      document.getElementById(elemento).removeChild(desplegable);
    }
    validar();
  }

  function mostrarTrabajos(elemento) {
    var numeroArticulo = elemento.split('-')[2];

    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()], 'tipoArticulos-'+numeroArticulo);
    var indexTipoArticulo = tipoArticulos[indexPedido()].indexOf(tipoArticulo);
    
    var divTipoArticulos = 'tipoArticulos-'+numeroArticulo;

    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[2];
      var trabajo = obtenerElemento(trabajos[indexPedido()][indexTipoArticulo], 'trabajos-'+numeroArticulo+'-'+numeroTipoArticulo);
      if(r.checked) {
        document.getElementById(divTipoArticulos).appendChild(trabajo);
        r.parentNode.classList.add('ta-seleccionado');
      } else {
        if (document.getElementById('trabajos-'+numeroArticulo+'-'+numeroTipoArticulo)) { 
          document.getElementById(divTipoArticulos).removeChild(trabajo);
          r.parentNode.classList.remove('ta-seleccionado');
        }
      }
    } 
    validar()
  }

  function mostrarPosiciones(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTipoArticulo = elemento.split('-')[3];
    var numeroTrabajo = elemento.split('-')[4];

    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()], 'tipoArticulos-'+numeroArticulo);
    var indexTipoArticulo = tipoArticulos[indexPedido()].indexOf(tipoArticulo);
    var trabajo = obtenerElemento(trabajos[indexPedido()][indexTipoArticulo], 'trabajos-'+numeroArticulo+'-'+numeroTipoArticulo);
    var indexTrabajo = trabajos[indexPedido()][indexTipoArticulo].indexOf(trabajo);
    var posicion = obtenerElemento(posiciones[indexPedido()][indexTipoArticulo][indexTrabajo], 'posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo);
    var indexPosicion = posiciones[indexPedido()][indexTipoArticulo][indexTrabajo].indexOf(posicion);

    var divTrabajos = 'trabajos-'+numeroArticulo+'-'+numeroTipoArticulo;

    var desplegable = obtenerElemento(desplegablesPosiciones[indexPedido()][indexTipoArticulo][indexTrabajo], 'desplegable-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo);

    if (document.getElementById('trabajo-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo).checked) {
      document.getElementById(divTrabajos).appendChild(posiciones[indexPedido()][indexTipoArticulo][indexTrabajo][indexPosicion]);
      document.getElementById(divTrabajos).appendChild(desplegable);
    } else {
      document.getElementById(divTrabajos).removeChild(document.getElementById('posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo));
      document.getElementById(divTrabajos).removeChild(desplegable);
    }
    validar()
  }

  function mostrarLogos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTipoArticulo = elemento.split('-')[3];
    var numeroTrabajo = elemento.split('-')[4];
    var numeroPosicion = elemento.split('-')[5];

    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()], 'tipoArticulos-'+numeroArticulo);
    var indexTipoArticulo = tipoArticulos[indexPedido()].indexOf(tipoArticulo);
    var trabajo = obtenerElemento(trabajos[indexPedido()][indexTipoArticulo], 'trabajos-'+numeroArticulo+'-'+numeroTipoArticulo);
    var indexTrabajo = trabajos[indexPedido()][indexTipoArticulo].indexOf(trabajo);
    var posicion = obtenerElemento(posiciones[indexPedido()][indexTipoArticulo][indexTrabajo], 'posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo);
    var indexPosicion = posiciones[indexPedido()][indexTipoArticulo][indexTrabajo].indexOf(posicion);
    var log = obtenerElemento(logos[indexPedido()][indexTipoArticulo][indexTrabajo][indexPosicion], 'logos-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion);
    var indexLogos = logos[indexPedido()][indexTipoArticulo][indexTrabajo][indexPosicion].indexOf(log);
    var divPosiciones = 'posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo;
    var desplegable = obtenerElemento(desplegablesLogos[indexPedido()][indexTipoArticulo][indexTrabajo][indexPosicion], 'desplegable-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion);

    if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion).checked) {
      document.getElementById(divPosiciones).appendChild(logos[indexPedido()][indexTipoArticulo][indexTrabajo][indexPosicion][indexLogos]);
      document.getElementById(divPosiciones).appendChild(desplegable);
    } else {
      document.getElementById(divPosiciones).removeChild(document.getElementById('logos-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion));
      document.getElementById(divPosiciones).removeChild(desplegable);
    }
    validar()
  }

  function logoSeleccionado(radios) {
    var numeroArticulo = radios.split('-')[1];
    var numeroTipoArticulo = radios.split('-')[2];
    var numeroTrabajo = radios.split('-')[3];
    var numeroPosicion = radios.split('-')[4];
    radios = document.getElementsByClassName(radios);
    for (let r of radios) {
      var numeroLogo = r.id.split('-')[5];
      if(r.checked) {
        r.parentNode.classList.add('ta-seleccionado');
      } else { 
        if (document.getElementById('logo-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion+'-'+numeroLogo)) {
          r.parentNode.classList.remove('ta-seleccionado');
        }  
      }
    } 
    validar()
  }

  function desplegable(elemento) {
    var indices = elemento.substring(elemento.indexOf('-'));
    if(document.getElementById(elemento).classList.contains('div-oculto')) {
      document.getElementById(elemento).classList.remove('div-oculto');
      document.getElementById('flecha'+indices).classList.remove('flecha-invertida');
    } else {
      document.getElementById(elemento).classList.add('div-oculto');
      document.getElementById('flecha'+indices).classList.add('flecha-invertida');
    }
  }

  function desplegarMenu() {
    var menu = document.getElementById('menu-lateral');
    var flecha = document.getElementById('flecha-lateral');
    var filtro = document.createElement('div');
    filtro.id = 'filtro';
    filtro.onclick = desplegarMenu;
    if(menu.classList.contains('menu-lateral-desplegado')) {
      menu.classList.remove('menu-lateral-desplegado');
      flecha.classList.remove('flecha-lateral-desplegada');
      document.getElementById('filtro').remove();
    } else {
      menu.classList.add('menu-lateral-desplegado');
      flecha.classList.add('flecha-lateral-desplegada');
      document.body.appendChild(filtro);
    }
  }

  // function updateImage(id, logo) {
  //   id = id.split('-')[5];
  //   var img = document.getElementById(logo);
  //   var logo = ;
  //   for (var i = 0; i < logo.length; i++) {
  //       if (logo[i].id == id) {
  //           img.src = '.' + logo[i].img;
  //           img.alt = '.' + logo[i].img;
  //           break;
  //       }
  //   }
  // }

  function updatePdf() {
    var option = document.getElementById('selectBoceto').value;
    var urlBoceto = null;
    for(var p=0; p < document.getElementById('selectPedido').length; p++) {
      if(bocetosUrl[p][option]) {
        var urlBoceto = '.' + bocetosUrl[p][option];
        break;
      }
    }
    var iframe = document.getElementsByTagName('iframe')[0];
    iframe.src = urlBoceto;
  }

</script>
  <div id='pagina'>
    <form id='formulario' action='resultado.php' method='post'>";
echo $divPedidos;
echo "
      <h1>Observaciones</h1>
      <textarea name='observaciones' placeholder='Escriba aquí otras demandas'></textarea>
      <input type='submit' id='enviar' disabled>
    </form>
  </div>
  <div id='listaCheck'>
    <div id='div-pdf'>
    </div>
  </div>
  
  <div id='menu-lateral'>
    <div id='desplegable-lateral' onclick='desplegarMenu()'>
      <div id='flecha-lateral'></div>
    </div>
    <div id='enlaces-menu'>
      <p>Trabajos</p>
      <a href='../CRUDS/bocetos/bocetos.php'>Bocetos</a>
      <a href='../CRUDS/clientes/clientes.php'>Clientes</a>
      <a href='../CRUDS/logos/logos.php'>Logos</a>
      <a href='../CRUDS/posicion/posiciones.php'>Posiciones</a>
      <a href='../CRUDS/tipoArticulo/tiposarticulo.php'>Tipos de artículo</a>
      <a href='../CRUDS/tipoTrabajo/tipostrabajo.php'>Tipos de trabajo</a>
    </div>
  </div>
  <div id='background'>
    <div class='ball' id='greenball1'/>
    <div class='ball' id='greenball2'/>
    <div class='ball' id='greenball3'/>
    <div class='ball' id='redball1'/>
    <div class='ball' id='redball2'/>
    <div class='ball' id='redball3'/>
    <div class='ball' id='blueball1'/>
    <div class='ball' id='blueball2'/>
    <div class='ball' id='blueball3'/>
  </div>
</body>
</html>";
?>

<script>
  function validarAr() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const marticulo = document.getElementsByClassName('articulo');

    // Se coge la lista lateral de artículos
    const listaCheck = document.getElementById('listaCheck');

    // Borramos todos los artículos de la lista de checks
    for (let msgArt of document.querySelectorAll('.msg-art')) {
      msgArt.remove();
    }

    // Por cada menu de posiciones...
    for (let ma of marticulo) {
      let valido = false;
      const id = ma.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('articulo');
      });

      const divPosicion = document.getElementById(ma.id);

      // Si hay un mensaje de error, lo borramos
      if (listaCheck.querySelector('ar')) {
        // divPosicion.querySelector('ar').remove();
        listaCheck.querySelector('ar').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        valido = true;
        // Añadimos los articulos a la lista de checks
        let msgArt = elementFromHtml("<div class='msg-art' id='msg-art-" + inf.id.split('-')[1] + "'><div class='msg-div'><p id='msg-art-titulo'>" + inf.value + "</p><img id='msg-img-" + inf.id.split('-')[1] + "' src='./cancelar.png' alt=''/></div></div>");
        listaCheck.appendChild(msgArt);
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + ma.id + " está completo.");
      } else if (!listaCheck.querySelector('ar')) {
        let msg = document.createElement('ar');
        msg.innerHTML = "<p>Seleccione al menos un articulo</p>";
        listaCheck.appendChild(msg);
      }
    }
  }

  function validarTar() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mtrabajo = document.getElementsByClassName('tipoArticulo');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let tar of document.querySelectorAll('.tar')) {
      tar.remove();
    }

    // Por cada menu de posiciones...
    for (let mta of mtrabajo) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }

      const id = mta.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('tipoArticulo-' + id[1]);
      });

      const divPosicion = document.getElementById(mta.id);

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) {
        if (valido[i]) {
          console.log("El menú con id " + mta.id + " está completo.");
        } else {
          // Si no hay mensajes de error, añadimos uno
          if (msgArt[i].id.split('-')[2] === id[1]) {
            if (!msgArt[i].querySelector('#tar-' + msgArt[i].id.split('-')[2]) && document.getElementById('tipoArticulos-' + id[1])) {
              let msg = elementFromHtml("<div class='tar' id='tar-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione un tipo de artículo</p></div>");
              msgArt[i].appendChild(msg);
            }
          }
        }
      }
    }
  }

  function validarTra() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mtrabajo = document.getElementsByClassName('trabajos');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let tra of document.querySelectorAll('.tra')) {
      tra.remove();
    }

    // Por cada menu de posiciones...
    for (let mt of mtrabajo) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }

      const id = mt.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('trabajo-' + id[1] + '-' + id[2]);
      });

      const divPosicion = document.getElementById(mt.id);

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {

            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico

      if (inputsFiltrados.length > 0) {
        for (var i = 0; i < valido.length; i++) {
          if (valido[i]) {
            console.log("El menú con id " + mt.id + " está completo.");
          } else {
            // Si no hay mensajes de error, añadimos uno
            if (msgArt[i].id.split('-')[2] === id[1]) {
              if (!msgArt[i].querySelector('#tra-' + msgArt[i].id.split('-')[2]) && document.getElementById("trabajos-" + id[1] + "-" + id[2])) {
                let msg = elementFromHtml("<div class='tra' id='tra-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione un trabajo</p></div>");
                msgArt[i].appendChild(msg);
              }
            }
          }
        }
      }
    }
  }

  function validarPos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mposicion = document.getElementsByClassName('posicion');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let pos of document.querySelectorAll('.pos')) {
      pos.remove();
    }

    // Por cada menu de posiciones...
    for (let mp of mposicion) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }
      const id = mp.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('posicion-' + id[1] + '-' + id[2] + '-' + id[3]);
      });

      const divPosicion = document.getElementById(mp.id);

      // Se recorre dichos checkboxes
      var inputsValidos = [];
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) {
        if (valido[i]) {
          console.log("El menú con id " + mp.id + " está completo.");
        } else {
          // Si no hay mensajes de error, añadimos uno
          if (msgArt[i].id.split('-')[2] === id[1]) {
            if (!msgArt[i].querySelector('#pos-' + msgArt[i].id.split('-')[2]) && document.getElementById("posicion-" + id[1] + '-' + id[2] + '-' + id[3])) {
              let msg = elementFromHtml("<div class='pos' id='pos-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione una posición</p></div>");
              msgArt[i].appendChild(msg);
            }
          }
        }
      }
    }
  }


  function validarLogos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mlogos = document.getElementsByClassName('logos');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let log of document.querySelectorAll('.log')) {
      log.remove();
    }

    // Por cada menu de posiciones...
    for (let ml of mlogos) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }

      const id = ml.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('logo-' + id[1] + '-' + id[2] + '-' + id[3] + '-' + id[4]);
      });

      const divPosicion = document.getElementById(ml.id);

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) {
        if (valido[i]) {
          console.log("El menú con id " + ml.id + " está completo.");
        } else {
          // Si no hay mensajes de error, añadimos uno
          if (msgArt[i].id.split('-')[2] === id[1]) {
            if (!msgArt[i].querySelector('#log-' + msgArt[i].id.split('-')[2]) && document.getElementById("logos-" + id[1] + '-' + id[2] + '-' + id[3] + '-' + id[4])) {
              let msg = elementFromHtml("<div class='log' id='log-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione un logo</p></div>");
              msgArt[i].appendChild(msg);
            }
          }
        }
      }
    }
  }

  function validarTodo() {

    var msgArt = document.querySelectorAll('.msg-art');
    for (m of msgArt) {

      var id = m.id.split('-')[2];
      var cb = document.getElementById("articulo-" + id)

      if (m.childNodes.length <= 1 && cb.checked) {
        m.getElementsByTagName('img')[0].src = './aceptar.png';
        m.classList.add('msg-art-verde');
      } else {
        m.getElementsByTagName('img')[0].src = './cancelar.png';
        m.classList.remove('msg-art-verde');
      }
    }
  }

  function boton() {
    let button = document.querySelector("#enviar");

    let msgArt = document.querySelectorAll('.msg-art');

    let listaCheck = document.querySelector("#listaCheck");
    button.disabled = false;

    if (listaCheck.contains(document.querySelector('ar'))) {
      button.disabled = true;
    }
    for (m of msgArt) {
      if (!m.classList.contains('msg-art-verde')) {
        button.disabled = true;
      }
    }
  }

  function validar() {
    validarAr();
    validarTar();
    validarTra();
    validarPos();
    validarLogos();
    validarTodo();
    boton();
  }
</script>