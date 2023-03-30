<?php
$articulos = json_decode(file_get_contents("http://localhost/API/articulos"), true);
$tiposTrabajos = json_decode(file_get_contents("http://localhost/API/tipo_trabajos"), true);
$tiposArticulos = json_decode(file_get_contents("http://localhost/API/tipo_articulos"), true);
$tiposPosiciones = array(
  0 => "Pecho izquierdo",
  1 => "Pecho derecho",
  2 => "Fuera bolsillo",
  3 => "Dentro bolsillo",
  4 => "Manga izquierda",
  5 => "Manga derecha",
  6 => "Espalda"
);
$tiposPosiciones = json_decode(file_get_contents("http://localhost/API/posiciones"), true);
$posicionesArticulos = json_decode(file_get_contents("http://localhost/API/posiciones_tipo_articulos/"), true);
$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);
$logos_encoded = json_encode($logos);
$numeroArticulos = count($articulos);
$numeroTrabajos = count($tiposTrabajos);
$numeroTipoArticulos = count($tiposArticulos);
$numeroPosicionesArticulos = count($posicionesArticulos);
$numeroPosiciones = count($tiposPosiciones);
$divArticulos = "";
$trabajos = array();
$arrayTipoArticulos = array();
$posiciones = array();
for ($i = 0; $i < $numeroArticulos; $i++) {
  $divArticulos .= "<div id=\"form-control-{$articulos[$i]['id']}\">";
  $divArticulos .= "<input type='checkbox' id=\"articulo-{$articulos[$i]['id']}\" name='articulo[]' value={{$articulos[$i]['descripcion']}} onclick='mostrarTrabajos(\"form-control-{$articulos[$i]['id']}\")'>";
  $divArticulos .= "<label for={{$articulos[$i]['id']}}>" . $articulos[$i]['descripcion'] . "</label><br>";
  $divArticulos .= "</div>";
  $trabajos[$i] = "<div class='trabajos' id=\"trabajos-{$articulos[$i]['id']}\"><h1>Trabajos: </h1><div class='coleccionHorizontal'>";
  for ($t = 0; $t < $numeroTrabajos; $t++) {
    $trabajos[$i] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\">";
    $trabajos[$i] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
    $trabajos[$i] .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
    $trabajos[$i] .= "</div>";
    $arrayTipoArticulos[$i][$t] = "<div class='tipoArticulo' id=\"tipoArticulos-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\"><h1>Tipo de articulo: </h1><fieldset><div class='coleccionHorizontal'>";
    for ($a = 0; $a < $numeroTipoArticulos; $a++) {
      $arrayTipoArticulos[$i][$t] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\">";
      $arrayTipoArticulos[$i][$t] .= "<label for={{$tiposArticulos[$a]['id']}}>";
      $arrayTipoArticulos[$i][$t] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\" name=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\")'>";
      $arrayTipoArticulos[$i][$t] .= $tiposArticulos[$a]['nombre'] . "</label><br>";
      $arrayTipoArticulos[$i][$t] .= "</div>";
      $posiciones[$i][$t][$a] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\"><h1>Posiciones: </h1><div class='coleccionHorizontal'>";
      for ($p = 0; $p < $numeroPosicionesArticulos; $p++) {
        if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
          // Obtiene la posición del array donde se encuentra el id de la posición
          $posIndex = array_search($posicionesArticulos[$p]['id_posicion'], array_column($tiposPosiciones, 'id'));
          $posiciones[$i][$t][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
          $posiciones[$i][$t][$a] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" class='posicion-checkbox' name='posicion-checkbox[]' value=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
          $posiciones[$i][$t][$a] .= "<label for={$posicionesArticulos[$p]['id_posicion']}>" . $tiposPosiciones[$posIndex]['descripcion'] . "</label><br>";
          $posiciones[$i][$t][$a] .= "</div>";
          $arrayLogos[$i][$t][$a][$p] = "<div class='logos'><select name='img-select[]' onchange='updateImage(this.value, \"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
          for ($l = 0; $l < count($logos); $l++) {
            $arrayLogos[$i][$t][$a][$p] .= "<option id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" value=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\">Logo " . $l + 1 . "</option>";
          }
          $arrayLogos[$i][$t][$a][$p] .= "</select>";
          $arrayLogos[$i][$t][$a][$p] .= "<img id=\"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" src=\".{$logos[0]['img']}\" alt=\".{$logos[0]['img']}\"/></div>";
        }
      }
      $posiciones[$i][$t][$a] .= "</div></div>";
    }
    $arrayTipoArticulos[$i][$t] .= "</div></fieldset></div>";
  }
  $trabajos[$i] .= "</div></div>";
}
$arrayTrabajos = json_encode($trabajos);
$arrayTipoArticulos = json_encode($arrayTipoArticulos);
$arrayPosiciones = json_encode($posiciones);
$arrayLogos = json_encode($arrayLogos);

echo "<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Index</title>
  <link rel='shortcut icon' href='favicon.png'>
  <link rel='stylesheet' href='styles2.css'>
</head>
<body>
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  var trabajos = $arrayTrabajos;
  var tipoArticulos = $arrayTipoArticulos;
  var posiciones = $arrayPosiciones;
  var logos = $arrayLogos;
  for (var i = 0; i < trabajos.length; i++) {
    trabajos[i] = elementFromHtml(trabajos[i]);
    for (var j = 0; j < tipoArticulos[i].length; j++) {
      tipoArticulos[i][j] = elementFromHtml(tipoArticulos[i][j]);
      for (var k = 0; k < posiciones[i][j].length; k++) {
        posiciones[i][j][k] = elementFromHtml(posiciones[i][j][k]);
        // for (var l = 0; l < logos[i][j][k].length; l++) {
        //   logos[i][j][k][l] = elementFromHtml(logos[i][j][k][l]);
        // }
      }
    }
  }
  console.log(posiciones);

  function obtenerElemento(array, id) {
    var elemento = array.find(a => a.id === id);
    return elemento;
  }

  function mostrarTrabajos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var trabajo = obtenerElemento(trabajos, 'trabajos-'+numeroArticulo);
    if (document.getElementById('articulo-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(trabajo);
    } else {
      document.getElementById(elemento).removeChild(trabajo);
    }
  }

  function mostrarTiposArticulos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];

    var trabajo = obtenerElemento(trabajos, 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos.indexOf(trabajo);

    var tipoArticulo = obtenerElemento(tipoArticulos[indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var divTrabajos = 'trabajos-'+numeroArticulo;
    if (document.getElementById('trabajo-'+numeroArticulo+'-'+numeroTrabajo).checked) {
      document.getElementById(divTrabajos).appendChild(tipoArticulo);
    } else {
      document.getElementById(divTrabajos).removeChild(tipoArticulo);
    }
  }

  function mostrarPosiciones(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];

    var trabajo = obtenerElemento(trabajos, 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos.indexOf(trabajo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var indexTipoArticulo = tipoArticulos[indexTrabajo].indexOf(tipoArticulo);

    var divTipoArticulos = 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo;

    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+numeroTrabajo);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[3];
      var pos = obtenerElemento(posiciones[indexTrabajo][indexTipoArticulo], 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo);
      if(r.checked) {
        document.getElementById(divTipoArticulos).appendChild(pos);
      } else {
        if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo)) { 
          document.getElementById(divTipoArticulos).removeChild(pos);
        }
      }
    } 
  }

  function mostrarLogos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroPosicion = elemento.split('-')[5];

    var trabajo = obtenerElemento(trabajos, 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos.indexOf(trabajo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var indexTipoArticulo = tipoArticulos[indexTrabajo].indexOf(tipoArticulo);
    var pos = obtenerElemento(posiciones[indexTrabajo][indexTipoArticulo], 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo);
    var indexPosicion = posiciones[indexTrabajo][indexTipoArticulo].indexOf(pos);

    var divPosiciones = 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo;

    if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(divPosiciones).appendChild(logos[indexTrabajo][indexTipoArticulo][indexPosicion][numeroPosicion]);
    } else {
      document.getElementById(divPosiciones).removeChild(logos[indexTrabajo][indexTipoArticulo][indexPosicion][numeroPosicion]);
    }
  }

  function updateImage(id, logo) {
    id = id.split('-')[5];
    var img = document.getElementById(logo);
    var logo = $logos_encoded;
    for (var i = 0; i < logo.length; i++) {
        if (logo[i].id == id) {
            img.src = '.' + logo[i].img;
            img.alt = '.' + logo[i].img;
            break;
        }
    }
  }

</script>
<form id='formulario' action='resultado.php' method='post'>
    <div class='articulo'>
      
      <h1>Articulos: </h1>";
echo $divArticulos;
echo "
    </div>
    <input type='submit'>
  </form>
</body>
</html>";
