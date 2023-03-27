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
for($i = 0; $i < $numeroArticulos; $i++) {
  $divArticulos .= "<div id=\"form-control-{$articulos[$i]['id']}\">";
  $divArticulos .= "<input type='checkbox' id=\"articulo-{$articulos[$i]['id']}\" name={{$articulos[$i]['descripcion']}} value={{$articulos[$i]['descripcion']}} onclick='mostrarTrabajos(\"form-control-{$articulos[$i]['id']}\")'>";
  $divArticulos .= "<label for={{$articulos[$i]['id']}}>" . $articulos[$i]['descripcion'] . "</label><br>";
  $divArticulos .= "</div>";
  $trabajos[$i] = "<div class='trabajos'><hr>Trabajos: <br>";
  for ($t = 0; $t < $numeroTrabajos; $t++) {
    $trabajos[$i] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\">";
    $trabajos[$i] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
    $trabajos[$i] .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
    $trabajos[$i] .= "</div>";
    $arrayTipoArticulos[$i][$t] = "<div class='tipoArticulo'><hr>Tipo de articulo: <br>";
    for ($a = 0; $a < $numeroTipoArticulos; $a++) {
      $arrayTipoArticulos[$i][$t] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\">";
      $arrayTipoArticulos[$i][$t] .= "<label for={{$tiposArticulos[$a]['id']}}>";
      $arrayTipoArticulos[$i][$t] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\" name='articuloRadio' value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\")'>";
      $arrayTipoArticulos[$i][$t] .= $tiposArticulos[$a]['nombre'] . "</label><br>";
      $arrayTipoArticulos[$i][$t] .= "</div>";
      $posiciones[$i][$t][$a] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\"><hr>Posiciones: <br>";
      for ($p = 0; $p < $numeroPosiciones; $p++) {
        $posiciones[$i][$t][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\">";
        $posiciones[$i][$t][$a] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$p}\" class='posicion-checkbox' name=\"" . $tiposPosiciones[$p] . "\" value=\"" . $tiposPosiciones[$p] . "\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
        $posiciones[$i][$t][$a] .= "<label for={$p}>" . $tiposPosiciones[$p] . "</label><br>";
        $posiciones[$i][$t][$a] .= "</div>";
        $arrayLogos[$i][$t][$a][$p] = "<div><select name='img' onchange='updateImage(this.value, \"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
        for ($l = 0; $l < count($logos); $l++) {
          $arrayLogos[$i][$t][$a][$p] .= "<option id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p-{$logos[$l]['id']}\" value='" . $logos[$l]['id'] . "'>Logo " . $l + 1 . "</option>";
        }
        $arrayLogos[$i][$t][$a][$p] .= "</select>";
        $arrayLogos[$i][$t][$a][$p] .= "<img id=\"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\" src='https://media.tenor.com/x8v1oNUOmg4AAAAd/rickroll-roll.gif' alt='logo' height='20%'/></div>";
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
  console.log(logos);

  function mostrarTrabajos(elemento) {
    var numeroElemento = elemento.split('-')[2];
    if (document.getElementById('articulo-'+numeroElemento).checked) {
      document.getElementById(elemento).appendChild(trabajos[numeroElemento-1]);
    } else {
      document.getElementById(elemento).removeChild(trabajos[numeroElemento-1]);
    }
  }

  function mostrarTiposArticulos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];
    if (document.getElementById('trabajo-'+numeroArticulo+'-'+numeroTrabajo).checked) {
      document.getElementById(elemento).appendChild(tipoArticulos[numeroArticulo-1][numeroTrabajo-1]);
    } else {
      document.getElementById(elemento).removeChild(tipoArticulos[numeroArticulo-1][numeroTrabajo-1]);
    }
  }

  function mostrarPosiciones(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+numeroTrabajo);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[3];
      console.log(posiciones[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1]);
      if(r.checked) {
        r.parentNode.appendChild(posiciones[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1]);
      } else {
        if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo)) { 
          r.parentNode.removeChild(posiciones[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1]);
          console.log(posiciones[numeroArticulo-1][numeroTrabajo-1][numeroTipoArticulo-1]);
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

  const form = document.querySelector('#formulario');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
  });

  function enviarFormulario(e) {
    e.preventDefault();
    pCheckbox = document.getElementsByClassName(posicion-checkbox);
    console.log(pCheckbox);
    pCheckbox.forEach(function(cb) {
      var cdTrabajo = cd.split('-')[0];
      var cdArticulo = cd.split('-')[1];
      if (cb.checked) {
        console.log(cb.id);
      }
    });
    return false;
  }
</script>
<form id='formulario' onsubmit='enviarFormulario(e)' method='post'>
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

// action='resultado.php'