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
$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);
$logos_encoded = json_encode($logos);
$numeroArticulos = count($articulos);
$numeroTrabajos = count($tiposTrabajos);
$numeroTipoArticulos = count($tiposArticulos);
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
  $trabajos[$i] = "<div class='trabajos' id=\"trabajos-{$articulos[$i]['id']}\"><hr>Trabajos: <br>";
  for ($t = 0; $t < $numeroTrabajos; $t++) {
    $trabajos[$i] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\">";
    $trabajos[$i] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
    $trabajos[$i] .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
    $trabajos[$i] .= "</div>";
    $arrayTipoArticulos[$i][$t] = "<div class='tipoArticulo' id=\"tipoArticulos-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\"><hr>Tipo de articulo: <br>";
    for ($a = 0; $a < $numeroTipoArticulos; $a++) {
      $arrayTipoArticulos[$i][$t] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\">";
      $arrayTipoArticulos[$i][$t] .= "<label for={{$tiposArticulos[$a]['id']}}>";
      $arrayTipoArticulos[$i][$t] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\" name='articuloRadio' value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\")'>";
      $arrayTipoArticulos[$i][$t] .= $tiposArticulos[$a]['nombre'] . "</label><br>";
      $arrayTipoArticulos[$i][$t] .= "</div>";
      $posiciones[$i][$t][$a] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\"><hr>Posiciones: <br>";
      for ($p = 0; $p < $numeroPosiciones; $p++) {
        $posiciones[$i][$t][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\">";
        $posiciones[$i][$t][$a] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$p}\" class='posicion-checkbox' name='posicion-checkbox[]' value=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
        $posiciones[$i][$t][$a] .= "<label for={$p}>" . $tiposPosiciones[$p] . "</label><br>";
        $posiciones[$i][$t][$a] .= "</div>";
        $arrayLogos[$i][$t][$a][$p] = "<div><select name='img-select[]' onchange='updateImage(this.value, \"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
        for ($l = 0; $l < count($logos); $l++) {
          $arrayLogos[$i][$t][$a][$p] .= "<option id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p-{$logos[$l]['id']}\" value=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p-{$logos[$l]['id']}\">Logo " . $l + 1 . "</option>";          
        }
        $arrayLogos[$i][$t][$a][$p] .= "</select>";
        $arrayLogos[$i][$t][$a][$p] .= "<img id=\"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\" src=\".{$logos[0]['img']}\" alt=\".{$logos[0]['img']}\" height='20%'/></div>";
      }
      $posiciones[$i][$t][$a] .= "<hr></div>";
    }
    $arrayTipoArticulos[$i][$t] .= "<hr></div>";
  }
  $trabajos[$i] .= "<hr></div>";
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
  <link rel='stylesheet' href='styles.css'>
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
        for (var l = 0; l < logos[i][j][k].length; l++) {
          logos[i][j][k][l] = elementFromHtml(logos[i][j][k][l]);
        }
      }
    }
  }

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
    var tipoArticulo = obtenerElemento(tipoArticulos[numeroArticulo-1], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    if (document.getElementById('trabajo-'+numeroArticulo+'-'+numeroTrabajo).checked) {
      document.getElementById(elemento).appendChild(tipoArticulo);
    } else {
      document.getElementById(elemento).removeChild(tipoArticulo);
    }
  }

  function mostrarPosiciones(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];
    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+numeroTrabajo);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[3];
      var pos = obtenerElemento(posiciones[numeroArticulo-1][numeroTrabajo-1], 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo);
      if(r.checked) {
        r.parentNode.appendChild(pos);
      } else {
        if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo)) { 
          r.parentNode.removeChild(pos);
        }
      }
    } 
  }

  function mostrarLogos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroPosicion = elemento.split('-')[5];
    if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(elemento).appendChild(logos[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1][numeroPosicion]);
    } else {
      document.getElementById(elemento).removeChild(logos[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1][numeroPosicion]);
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
      <hr>
      Articulos: <br>";
echo $divArticulos;
echo "<hr>
    </div>
    <input type='submit'>
  </form>
</body>
</html>";
