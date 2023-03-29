<?php
$tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);
$tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);
$tiposPosiciones = array(
  0 => "Pecho izquierdo",
  1 => "Pecho derecho",
  2 => "Fuera bolsillo",
  3 => "Dentro bolsillo",
  4 => "Manga izquierda",
  5 => "Manga derecha",
  6 => "Espalda"
);
$logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
$logos_encoded = json_encode($logos);
$numeroTrabajos = count($tiposTrabajos);
$numeroArticulos = count($tiposArticulos);
$numeroPosiciones = count($tiposPosiciones);
$trabajos = "";
$articulos = array();
$posiciones = array();
for ($t = 0; $t < $numeroTrabajos; $t++) {
  $trabajos .= "<div id='form-control-$t'>";
  $trabajos .= "<input type='checkbox' id=\"trabajo-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarArticulos(\"form-control-$t\")'>";
  $trabajos .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
  $trabajos .= "</div>";
  $articulos[$t] = "<div class='articulo'><hr>Tipo de articulo: <br>";
  for ($a = 0; $a < $numeroArticulos; $a++) {
    $articulos[$t] .= "<div id='form-control-$t-$a'>";
    $articulos[$t] .= "<input type='checkbox' id=\"articulo-$t-{$tiposArticulos[$a]['id']}\" name={{$tiposArticulos[$a]['nombre']}} value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-$t-$a\")'>";
    $articulos[$t] .= "<label for={{$tiposArticulos[$a]['id']}}>" . $tiposArticulos[$a]['nombre'] . "</label><br>";
    $articulos[$t] .= "</div>";
    $posiciones[$t][$a] = "<div class='posicion'><hr>Posiciones: <br>";
    for ($p = 0; $p < $numeroPosiciones; $p++) {
      $posiciones[$t][$a] .= "<div id='form-control-$t-$a-$p'>";
      $posiciones[$t][$a] .= "<input type='checkbox' id=\"posicion-$t-$a-{$p}\" class='posicion-checkbox' name=\"".$tiposPosiciones[$p]."\" value=\"".$tiposPosiciones[$p]."\" onclick='mostrarLogos(\"form-control-$t-$a-$p\")'>";
      $posiciones[$t][$a] .= "<label for={$p}>" . $tiposPosiciones[$p] . "</label><br>";
      $posiciones[$t][$a] .= "</div>";
      $arrayLogos[$t][$a][$p] = "<div><select name='img' onchange='updateImage(this.value, \"logo-img-$t-$a-$p\")'>";
      for ($l = 0; $l < count($logos); $l++) {
        $arrayLogos[$t][$a][$p] .= "<option id='logo-$t-$a-$p-$l' value='" . $logos[$l]['id'] . "'>Logo " . $l + 1 . "</option>";
      }
      $arrayLogos[$t][$a][$p] .= "</select>";
      $arrayLogos[$t][$a][$p] .= "<img id='logo-img-$t-$a-$p' src='https://media.tenor.com/x8v1oNUOmg4AAAAd/rickroll-roll.gif' alt='logo' height='20%'/></div>";
    }
    $posiciones[$t][$a] .= "<hr></div>";
  }
  $articulos[$t] .= "<hr></div>";
}

$arrayArticulos = json_encode($articulos);
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

  var articulos = $arrayArticulos;
  var posiciones = $arrayPosiciones;
  var logos = $arrayLogos;
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    for (var j = 0; j < posiciones[i].length; j++) {
      posiciones[i][j] = elementFromHtml(posiciones[i][j]);
      for (var k = 0; k < logos[i][j].length; k++) {
        logos[i][j][k] = elementFromHtml(logos[i][j][k]);
      }
    }
  }

  function mostrarArticulos(elemento) {
    var numeroElemento = elemento.split('-')[2];
    var numeroTrabajo = parseInt(numeroElemento)+1
    if (document.getElementById('trabajo-'+numeroTrabajo).checked) {
      document.getElementById(elemento).appendChild(articulos[numeroElemento]);
    } else {
      document.getElementById(elemento).removeChild(articulos[numeroElemento]);
    }
  }

  function mostrarPosiciones(elemento) {
    var numeroPadre = elemento.split('-')[2];
    var numeroElemento = elemento.split('-')[3];
    var numeroArticulo = parseInt(numeroElemento)+1;
    if (document.getElementById('articulo-'+numeroPadre+'-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(posiciones[numeroPadre][numeroElemento]);
    } else {
      document.getElementById(elemento).removeChild(posiciones[numeroPadre][numeroElemento]);
    }
  }

  function mostrarLogos(elemento) {
    var numeroTrabajo = elemento.split('-')[2];
    var numeroArticulo = elemento.split('-')[3];
    var numeroPosicion = elemento.split('-')[4];
    if (document.getElementById('posicion-'+numeroTrabajo+'-'+numeroArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(elemento).appendChild(logos[numeroTrabajo][numeroArticulo][numeroPosicion]);
    } else {
      document.getElementById(elemento).removeChild(logos[numeroTrabajo][numeroArticulo][numeroPosicion]);
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
      if (cb.checked) {
        console.log(cb.id);
      }
    });
  }
</script>
<form id='formulario' onsubmit='enviarFormulario(e)' method='post'>
    <div class='trabajo'>
      <hr>
      Tipo de trabajo: <br>";
echo $trabajos;
echo "<hr>
    </div>
    <input type='submit'>
  </form>
</body>
</html>";

// action='resultado.php'
