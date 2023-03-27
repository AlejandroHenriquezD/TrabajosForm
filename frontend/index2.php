<?php
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
$numeroTrabajos = count($tiposTrabajos);
$numeroArticulos = count($tiposArticulos);
$numeroPosiciones = count($tiposPosiciones);
$trabajos = "";
$articulos = array();
$posiciones = array();
for ($t = 0; $t < $numeroTrabajos; $t++) {
  $trabajos .= "<div id=\"form-control-{$tiposTrabajos[$t]['id']}\">";
  $trabajos .= "<input type='checkbox' id=\"trabajo-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarArticulos(\"form-control-{$tiposTrabajos[$t]['id']}\")'>";
  $trabajos .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
  $trabajos .= "</div>";
  $articulos[$t] = "<div class='articulo'><hr>Tipo de articulo: <br>";
  for ($a = 0; $a < $numeroArticulos; $a++) {
    $articulos[$t] .= "<div id=\"form-control-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\">";
    $articulos[$t] .= "<label for={{$tiposArticulos[$a]['id']}}>";
    $articulos[$t] .= "<input type='radio' class='articuloRadio' id=\"articulo-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\" name='articuloRadio' value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\")'>";
    $articulos[$t] .= $tiposArticulos[$a]['nombre'] . "</label><br>";
    $articulos[$t] .= "</div>";
    $posiciones[$t][$a] = "<div class='posicion'><hr>Posiciones: <br>";
    for ($p = 0; $p < $numeroPosiciones; $p++) {
      $posiciones[$t][$a] .= "<div id=\"form-control-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\">";
      $posiciones[$t][$a] .= "<input type='checkbox' id=\"posicion-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$p}\" class='posicion-checkbox' name=\"" . $tiposPosiciones[$p] . "\" value=\"" . $tiposPosiciones[$p] . "\" onclick='mostrarLogos(\"form-control-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
      $posiciones[$t][$a] .= "<label for={$p}>" . $tiposPosiciones[$p] . "</label><br>";
      $posiciones[$t][$a] .= "</div>";
      $arrayLogos[$t][$a][$p] = "<div><select name='img' onchange='updateImage(this.value, \"logo-img-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
      for ($l = 0; $l < count($logos); $l++) {
        $arrayLogos[$t][$a][$p] .= "<option id=\"logo-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p-{$logos[$l]['id']}\" value='" . $logos[$l]['id'] . "'>Logo " . $l + 1 . "</option>";
      }
      $arrayLogos[$t][$a][$p] .= "</select>";
      $arrayLogos[$t][$a][$p] .= "<img id=\"logo-img-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\" src='https://media.tenor.com/x8v1oNUOmg4AAAAd/rickroll-roll.gif' alt='logo' height='20%'/></div>";
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
    if (document.getElementById('trabajo-'+numeroElemento).checked) {
      document.getElementById(elemento).appendChild(articulos[numeroElemento-1]);
    } else {
      document.getElementById(elemento).removeChild(articulos[numeroElemento-1]);
    }
  }

  function mostrarPosiciones(elemento) {
    var radios = document.getElementsByClassName('articuloRadio');
    for (let r of radios) {
      var numeroPadre = r.id.split('-')[1];
      var numeroElemento = r.id.split('-')[2];
      if(r.checked) {
        r.parentNode.appendChild(posiciones[numeroPadre-1][numeroElemento-1]);
      } else {
        if (posiciones[numeroPadre-1][numeroElemento-1].parentNode == r.parentNode) { 
          r.parentNode.removeChild(posiciones[numeroPadre-1][numeroElemento-1]);
        }
      }
    }
    
  }

  function mostrarLogos(elemento) {
    var numeroTrabajo = elemento.split('-')[2];
    var numeroArticulo = elemento.split('-')[3];
    var numeroPosicion = elemento.split('-')[4];
    if (document.getElementById('posicion-'+numeroTrabajo+'-'+numeroArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(elemento).appendChild(logos[numeroTrabajo-1][numeroArticulo-1][numeroPosicion]);
    } else {
      document.getElementById(elemento).removeChild(logos[numeroTrabajo-1][numeroArticulo-1][numeroPosicion]);
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
