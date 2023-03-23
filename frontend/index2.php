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
      $posiciones[$t][$a] .= "<input type='checkbox' id=\"posicion-$t-$a-{$p}\" name=\"".$tiposPosiciones[$p]."\" value=\"".$tiposPosiciones[$p]."\" onclick='mostrarLogos(\"form-control-$t-$a-$p\")'>";
      $posiciones[$t][$a] .= "<label for={$p}>" . $tiposPosiciones[$p] . "</label><br>";
      $posiciones[$t][$a] .= "</div>";
    }
    $posiciones[$t][$a] .= "<hr></div>";
  }
  $articulos[$t] .= "<hr></div>";
}
$arrayArticulos = json_encode($articulos);
$arrayPosiciones = json_encode($posiciones, JSON_HEX_QUOT | JSON_HEX_TAG);
// echo $arrayPosiciones;
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
  console.log(posiciones);
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    for (var j = 0; j < posiciones.length; j++) {
      posiciones[i][j] = elementFromHtml(posiciones[i][j]);
      console.log(posiciones[i][j]);
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
    var numeroArticulo = parseInt(numeroElemento)+1
    console.log(numeroElemento);
    if (document.getElementById('articulo-'+numeroPadre+'-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(posiciones[numeroPadre][numeroElemento]);
    } else {
      document.getElementById(elemento).removeChild(posiciones[numeroPadre][numeroElemento]);
    }
  }
</script>
<form action='resultado.php' method='post'>
    <div class='trabajo'>
      <hr>
      Tipo de trabajo: <br>";
echo $trabajos;
echo "<hr>
    </div>
    Logo: <input type='text' name='logo'><br>
    <input type='submit'>
  </form>
</body>
</html>";
