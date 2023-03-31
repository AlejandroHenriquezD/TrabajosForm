<?php
$pedidos = json_decode(file_get_contents("http://localhost/API/pedidos"), true);
$pedidosArticulos = json_decode(file_get_contents("http://localhost/API/pedidos_articulos"), true);
$articulos = json_decode(file_get_contents("http://localhost/API/articulos"), true);
$tiposTrabajos = json_decode(file_get_contents("http://localhost/API/tipo_trabajos"), true);
$tiposArticulos = json_decode(file_get_contents("http://localhost/API/tipo_articulos"), true);
$tiposPosiciones = json_decode(file_get_contents("http://localhost/API/posiciones"), true);
$posicionesArticulos = json_decode(file_get_contents("http://localhost/API/posiciones_tipo_articulos/"), true);
$logos = json_decode(file_get_contents("http://localhost/API/logos"), true);
$logos_encoded = json_encode($logos);
$numeroPedidos = count($pedidos);
$numeroPedidosArticulos = count($pedidosArticulos);
$numeroArticulos = count($articulos);
$numeroTrabajos = count($tiposTrabajos);
$numeroTipoArticulos = count($tiposArticulos);
$numeroPosicionesArticulos = count($posicionesArticulos);
$numeroPosiciones = count($tiposPosiciones);

$arrayArticulos = array();
$trabajos = array();
$arrayTipoArticulos = array();
$posiciones = array();
$relacion = array();
$divPedidos = "<div id='pedidos'><h1>Pedido</h1><select onchange=mostrarArticulos(this.value)>";
for ($o = 0; $o < $numeroPedidos; $o++) {
  $divPedidos .= "<option id={$pedidos[$o]['id']} value={$pedidos[$o]['id']}>{$pedidos[$o]['id']}</option>";
}
$divPedidos .= "</select>";
// Necesito un if para el id_pedido
$pedidos[1]['id'];
for ($o = 0; $o < $numeroPedidos; $o++) {
  $arrayArticulos[$o] = "<div class='articulo' id='articulos-$o'><h1>Articulos </h1>";

  for ($j = 0; $j < $numeroPedidosArticulos; $j++) {
    if ($pedidosArticulos[$j]['id_pedido'] == $pedidos[$o]['id']) {
      $relacion[$o][$j] = $pedidosArticulos[$j]['id_articulo'];
    } else {
      $relacion[$o][$j] = null;
    }
    for ($i = 0; $i < $numeroArticulos; $i++) {
      if ($relacion[$o][$j] == $articulos[$i]['id']) {
        echo $relacion[$o][$j];
        $arrayArticulos[$o] .= "<div id=\"form-control-{$articulos[$i]['id']}\">";
        $arrayArticulos[$o] .= "<input type='checkbox' id=\"articulo-{$articulos[$i]['id']}\" name='articulo[]' value={{$articulos[$i]['descripcion']}} onclick='mostrarTrabajos(\"form-control-{$articulos[$i]['id']}\")'>";
        $arrayArticulos[$o] .= "<label for={{$articulos[$i]['id']}}>" . $articulos[$i]['descripcion'] . "</label><br>";
        $arrayArticulos[$o] .= "</div>";
      }
      $trabajos[$o][$i] = "<div class='trabajos' id=\"trabajos-{$articulos[$i]['id']}\"><h1>Trabajos</h1><div class='coleccionHorizontal'>";
      for ($t = 0; $t < $numeroTrabajos; $t++) {
        $trabajos[$o][$i] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\">";
        $trabajos[$o][$i] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
        $trabajos[$o][$i] .= "<label for={{$tiposTrabajos[$t]['id']}}>" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
        $trabajos[$o][$i] .= "</div>";
        $arrayTipoArticulos[$o][$i][$t] = "<div class='tipoArticulo' id=\"tipoArticulos-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\">";
        $arrayTipoArticulos[$o][$i][$t] .= "<div class='seleccionado'><h1>{$tiposTrabajos[$t]['nombre']}</h1></div><h1>Tipo de articulo: </h1><div class='coleccionHorizontal'>";
        for ($a = 0; $a < $numeroTipoArticulos; $a++) {
          $arrayTipoArticulos[$o][$i][$t] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\">";
          $arrayTipoArticulos[$o][$i][$t] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\" name=\"articuloRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}\" value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\")'>";
          $arrayTipoArticulos[$o][$i][$t] .= "<img src=\".{$tiposArticulos[$a]['img']}\" alt=\".{$tiposArticulos[$a]['img']}\"/>";
          $arrayTipoArticulos[$o][$i][$t] .= "<br></div>";
          $posiciones[$o][$i][$t][$a] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}\"><h1>Posiciones: </h1><div class='coleccionHorizontal'>";
          for ($p = 0; $p < $numeroPosicionesArticulos; $p++) {
            if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
              // Obtiene la posición del array donde se encuentra el id de la posición
              $posIndex = array_search($posicionesArticulos[$p]['id_posicion'], array_column($tiposPosiciones, 'id'));
              $posiciones[$o][$i][$t][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
              $posiciones[$o][$i][$t][$a] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" class='posicion-checkbox' name='posicion-checkbox[]' value=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
              $posiciones[$o][$i][$t][$a] .= "<label for={$posicionesArticulos[$p]['id_posicion']}>" . $tiposPosiciones[$posIndex]['descripcion'] . "</label><br>";
              $posiciones[$o][$i][$t][$a] .= "</div>";
            }
            $arrayLogos[$o][$i][$t][$a][$p] = "<div class='logos' id=\"logos-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
            if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
              $arrayLogos[$o][$i][$t][$a][$p] .= "<div class='seleccionado'><h1>{$tiposPosiciones[$posIndex]['descripcion']}</h1></div>";
            }
            $arrayLogos[$o][$i][$t][$a][$p] .= "<select name='img-select[]' onchange='updateImage(this.value, \"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-$p\")'>";
            for ($l = 0; $l < count($logos); $l++) {
              $arrayLogos[$o][$i][$t][$a][$p] .= "<option id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" value=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\">Logo " . $l + 1 . "</option>";
            }
            $arrayLogos[$o][$i][$t][$a][$p] .= "</select>";
            $arrayLogos[$o][$i][$t][$a][$p] .= "<img id=\"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" src=\".{$logos[0]['img']}\" alt=\".{$logos[0]['img']}\"/></div>";
          }
          $posiciones[$o][$i][$t][$a] .= "</div></div>";
        }
        $arrayTipoArticulos[$o][$i][$t] .= "</div></div>";
      }
      $trabajos[$o][$i] .= "</div></div>";

    }
  }
  $arrayArticulos[$o] .= "</div>";
}
$divPedidos .= "</div>";

$arrayArticulos = json_encode($arrayArticulos);
$arrayTrabajos = json_encode($trabajos);
$arrayTipoArticulos = json_encode($arrayTipoArticulos);
$arrayPosiciones = json_encode($posiciones);
$arrayLogos = json_encode($arrayLogos);
$art = json_encode($articulos);
$tra = json_encode($tiposTrabajos);
$tart = json_encode($tiposArticulos);
$pos = json_encode($tiposPosiciones);

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
<body onload='añadirPrimero();'>
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  var articulos = $arrayArticulos;
  var trabajos = $arrayTrabajos;
  var tipoArticulos = $arrayTipoArticulos;
  var posiciones = $arrayPosiciones;
  var logos = $arrayLogos;
  console.log(logos);
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    for (var j = 0; j < trabajos.length; j++) {
      trabajos[i][j] = elementFromHtml(trabajos[i][j]);
      for (var k = 0; k < tipoArticulos[i].length; k++) {
        tipoArticulos[i][j][k] = elementFromHtml(tipoArticulos[i][j][k]);
        for (var l = 0; l < posiciones[i][j].length; l++) {
          posiciones[i][j][k][l] = elementFromHtml(posiciones[i][j][k][l]);
          for (var m = 0; m < logos[i][j][k].length; m++) {
            // logos[i][j][k][l][m] = elementFromHtml(logos[i][j][k][l][m]);
          }
        }
      }
    }
  }
  console.log(articulos);


  function obtenerElemento(array, id) {
    var elemento = array.find(a => a.id === id);
    return elemento;
  }

  var elementoAntiguo = 0;
  
  function añadirPrimero() {
    document.getElementById('pedidos').appendChild(articulos[0]);
  }

  function mostrarArticulos(elemento) {
    elemento = elemento-1;
    var articulo = obtenerElemento(articulos, 'articulos-'+elemento);
    var articuloAntiguo = obtenerElemento(articulos, 'articulos-'+elementoAntiguo);
    document.getElementById('pedidos').removeChild(articuloAntiguo);
    document.getElementById('pedidos').appendChild(articulo);
    elementoAntiguo = elemento;
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
        r.parentNode.classList.add('ta-seleccionado');
      } else {
        if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo)) { 
          r.parentNode.classList.remove('ta-seleccionado');
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
    var log = obtenerElemento(logos[indexTrabajo][indexTipoArticulo][indexPosicion], 'logos-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion);
    var indexLogos = logos[indexTrabajo][indexTipoArticulo][indexPosicion].indexOf(log);
    
    var divPosiciones = 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo;

    if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(divPosiciones).appendChild(logos[indexTrabajo][indexTipoArticulo][indexPosicion][indexLogos]);
      validarPos();
    } else {
      document.getElementById(divPosiciones).removeChild(document.getElementById('logos-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion));
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
  <div id='pagina'>
    <form id='formulario' action='' method='post'>";
echo $divPedidos;
echo "
      <input type='submit'>
    </form>
    <button id='validar'>Validar</button>
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
  function validarPos() {

    //Se cogen los diferentes menus de posiciones
    const coleccionPos = document.getElementsByClassName('posicion');

    //se cogen todos los checkbox de posicion
    const checkboxes = document.getElementsByClassName('posicion-checkbox');

    //Por cada menu de posiciones...
    for (let cp of coleccionPos) {

      var valido = false;

      //se recogen todos sus checkboxes
      const checkboxFiltrados = Array.from(checkboxes).filter(checkbox => {
        return checkbox.id.includes(cp.id);
      });

      // Creamos un objeto para guardar el estado de los checkboxes
      const estadosCheckboxes = {};

      // se recorre dichos checkboxes
      for (let cb of checkboxFiltrados) {
        // Guardamos el estado del checkbox en el objeto
        estadosCheckboxes[cb.id] = cb.checked;
        //Si hay al menos uno seleccionado se da por válido
        if (cb.checked) {
          valido = true;
        }
      }

      const divPosicion = document.getElementById(cp.id);
      //Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + cp.id + " está completo.");
        //y borramos el mensaje de error
        divPosicion.innerHTML = divPosicion.innerHTML.replace(/<br>Error: Debe seleccionar al menos una opción\./g, '');
        // Recorremos el objeto con los estados de los checkboxes
        for (const [id, estado] of Object.entries(estadosCheckboxes)) {
          // Establecemos el estado del checkbox
          const checkbox = document.getElementById(id);
          if (checkbox) {
            checkbox.checked = estado;
          }
        }
      } else {
        console.log("El menú con id " + cp.id + " está incompleto.");
        if (!divPosicion.innerHTML.includes('Error:')) {
          divPosicion.innerHTML += "<br>Error: Debe seleccionar al menos una opción.";
        }
      }
    }
  }


  const boton = document.getElementById('validar');

  boton.addEventListener('click', function (evento) {
    evento.preventDefault();
    validarPos();
  });
</script>