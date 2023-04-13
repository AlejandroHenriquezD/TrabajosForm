<?php
$pedidos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos"), true);
$pedidosArticulos = json_decode(file_get_contents("http://localhost/trabajosform/pedidos_articulos"), true);
$articulos = json_decode(file_get_contents("http://localhost/trabajosform/articulos"), true);
$tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);
$tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);
$tiposPosiciones = json_decode(file_get_contents("http://localhost/trabajosform/posiciones"), true);
$posicionesArticulos = json_decode(file_get_contents("http://localhost/trabajosform/posiciones_tipo_articulos/"), true);
$logos = json_decode(file_get_contents("http://localhost/trabajosform/logos"), true);
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
$arrayLogos = array();
$posiciones = array();
$arrayLogos = array();
$relacion = array();
$desplegables = array();
$divPedidos = "<div id='pedidos'><h1>Pedido</h1><select name='selectPedido[]' id='selectPedido' onchange=mostrarArticulos(this.value)>";
for ($o = 0; $o < $numeroPedidos; $o++) {
  $divPedidos .= "<option id={$pedidos[$o]['id']} value={$pedidos[$o]['id']}>{$pedidos[$o]['id']}</option>";
}
$divPedidos .= "</select>";
// Necesito un if para el id_pedido
$pedidos[1]['id'];
for ($o = 0; $o < $numeroPedidos; $o++) {
  $arrayArticulos[$o] = "<div class='articulo' id='articulos-{$pedidos[$o]['id']}'><h1>Articulos </h1>";

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
        $arrayTipoArticulos[$o][$i][$t] .= "<div class='seleccionado'><h1>{$tiposTrabajos[$t]['nombre']}</h1></div><h1>Tipo de articulo</h1><div class='slider'><div class='coleccion'>";
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
              $arrayLogos[$o][$i][$t][$a][$p] .= "<div class='seleccionado'><h1>{$tiposPosiciones[$posIndex]['descripcion']}</h1></div><h1>Logotipo</h1><div class='slider'><div class='coleccion'>";
              for ($l = 0; $l < count($logos); $l++) {
                $arrayLogos[$o][$i][$t][$a][$p] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\">";
                $arrayLogos[$o][$i][$t][$a][$p] .= "<input type='radio' class=\"logoRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" name=\"img-input[grupo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}]\" value=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" onclick='logoSeleccionado(\"logoRadio-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
                $arrayLogos[$o][$i][$t][$a][$p] .= "<img src=\".{$logos[$l]['img']}\" alt=\".{$logos[$l]['img']}\"/>";
                $arrayLogos[$o][$i][$t][$a][$p] .= "<br></div>";
              }
            }

            // $arrayLogos[$o][$i][$t][$a][$p] .= "<select name='img-select[]' onchange='updateImage(this.value, \"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
            // for ($l = 0; $l < count($logos); $l++) {
            //   $arrayLogos[$o][$i][$t][$a][$p] .= "<option id=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" value=\"logo-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\">Logo " . $l + 1 . "</option>";
            // }
            // $arrayLogos[$o][$i][$t][$a][$p] .= "</select>";
            // $arrayLogos[$o][$i][$t][$a][$p] .= "<img id=\"logo-img-{$articulos[$i]['id']}-{$tiposTrabajos[$t]['id']}-{$tiposArticulos[$a]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" src=\".{$logos[0]['img']}\" alt=\".{$logos[0]['img']}\"/></div>";

          }
          $posiciones[$o][$i][$t][$a] .= "</div></div>";
        }
        $arrayTipoArticulos[$o][$i][$t] .= "</div></div></div>";
      }
      $trabajos[$o][$i] .= "</div>";
      $desplegables[$o][$i] = "<div class='desplegable' id=\"desplegable-{$articulos[$i]['id']}\" onclick='desplegable(\"trabajos-{$articulos[$i]['id']}\")'><div class='flecha' id=\"flecha-{$articulos[$i]['id']}\"></div></div>";
    }
  }
  $arrayArticulos[$o] .= "</div>";
}
$divPedidos .= "</div>";

$arrayArticulos = json_encode($arrayArticulos);
$arrayTrabajos = json_encode($trabajos);
$desplegables = json_encode($desplegables);
$arrayTipoArticulos = json_encode($arrayTipoArticulos);
$arrayPosiciones = json_encode($posiciones);
$arrayLogos = json_encode($arrayLogos);
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
<body onload='añadirPrimero();'>
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  var articulos = $arrayArticulos;
  var trabajos = $arrayTrabajos;
  var desplegables = $desplegables;
  var tipoArticulos = $arrayTipoArticulos;
  var posiciones = $arrayPosiciones;
  var logos = $arrayLogos;
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    for (var j = 0; j < trabajos[i].length; j++) {
      trabajos[i][j] = elementFromHtml(trabajos[i][j]);
      desplegables[i][j] = elementFromHtml(desplegables[i][j]);
      for (var k = 0; k < tipoArticulos[i][j].length; k++) {
        tipoArticulos[i][j][k] = elementFromHtml(tipoArticulos[i][j][k]);
        for (var l = 0; l < posiciones[i][j][k].length; l++) {
          posiciones[i][j][k][l] = elementFromHtml(posiciones[i][j][k][l]);
          for (var m = 0; m < logos[i][j][k][l].length; m++) {
            logos[i][j][k][l][m] = elementFromHtml(logos[i][j][k][l][m]);
          }
        }
      }
    }
  }

  var elementoActual = null;
  
  function añadirPrimero() {
    document.getElementById('pedidos').appendChild(articulos[0]);
    var select = document.getElementById('selectPedido');
    elementoActual = select.options[select.selectedIndex].value;
  }

  function obtenerElemento(array, id) {
    var elemento = array.find(a => a.id === id);
    return elemento;
  }

  function indexPedido() {
    var select = document.getElementById('selectPedido');
    return select.selectedIndex;
  }

  function mostrarArticulos(elemento) {
    var articulo = obtenerElemento(articulos, 'articulos-'+elemento);
    var articuloAntiguo = obtenerElemento(articulos, 'articulos-'+elementoActual);
    document.getElementById('pedidos').removeChild(articuloAntiguo);
    document.getElementById('pedidos').appendChild(articulo);
    elementoActual = elemento;
  }

  function mostrarTrabajos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var trabajo = obtenerElemento(trabajos[indexPedido()], 'trabajos-'+numeroArticulo);
    var desplegable = obtenerElemento(desplegables[indexPedido()], 'desplegable-'+numeroArticulo);
    if (document.getElementById('articulo-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(trabajo);
      document.getElementById(elemento).appendChild(desplegable);
      validarAr();      
    } else {
      document.getElementById(elemento).removeChild(trabajo);
      document.getElementById(elemento).removeChild(desplegable);
    }
  }

  function mostrarTiposArticulos(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];

    var trabajo = obtenerElemento(trabajos[indexPedido()], 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos[indexPedido()].indexOf(trabajo);

    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()][indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var divTrabajos = 'trabajos-'+numeroArticulo;
    if (document.getElementById('trabajo-'+numeroArticulo+'-'+numeroTrabajo).checked) {
      document.getElementById(divTrabajos).appendChild(tipoArticulo);
      validarTra();      
    } else {
      document.getElementById(divTrabajos).removeChild(tipoArticulo);
    }
  }

  function mostrarPosiciones(elemento) {
    var numeroArticulo = elemento.split('-')[2];
    var numeroTrabajo = elemento.split('-')[3];

    var trabajo = obtenerElemento(trabajos[indexPedido()], 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos[indexPedido()].indexOf(trabajo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()][indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var indexTipoArticulo = tipoArticulos[indexPedido()][indexTrabajo].indexOf(tipoArticulo);
    var divTipoArticulos = 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo;

    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+numeroTrabajo);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[3];
      var pos = obtenerElemento(posiciones[indexPedido()][indexTrabajo][indexTipoArticulo], 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo);
      if(r.checked) {
        document.getElementById(divTipoArticulos).appendChild(pos);
        r.parentNode.classList.add('ta-seleccionado');
        validarTar();
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

    var trabajo = obtenerElemento(trabajos[indexPedido()], 'trabajos-'+numeroArticulo);
    var indexTrabajo = trabajos[indexPedido()].indexOf(trabajo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()][indexTrabajo], 'tipoArticulos-'+numeroArticulo+'-'+numeroTrabajo);
    var indexTipoArticulo = tipoArticulos[indexPedido()][indexTrabajo].indexOf(tipoArticulo);
    var pos = obtenerElemento(posiciones[indexPedido()][indexTrabajo][indexTipoArticulo], 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo);
    var indexPosicion = posiciones[indexPedido()][indexTrabajo][indexTipoArticulo].indexOf(pos);
    var log = obtenerElemento(logos[indexPedido()][indexTrabajo][indexTipoArticulo][indexPosicion], 'logos-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion);
    var indexLogos = logos[indexPedido()][indexTrabajo][indexTipoArticulo][indexPosicion].indexOf(log);
    
    var divPosiciones = 'posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo;

    if (document.getElementById('posicion-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion).checked) {
      document.getElementById(divPosiciones).appendChild(logos[indexPedido()][indexTrabajo][indexTipoArticulo][indexPosicion][indexLogos]);
      validarPos()
    } else {
      document.getElementById(divPosiciones).removeChild(document.getElementById('logos-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion));
    }
  }

  function logoSeleccionado(radios) {
    var numeroArticulo = radios.split('-')[1];
    var numeroTrabajo = radios.split('-')[2];
    var numeroTipoArticulo = radios.split('-')[3];
    var numeroPosicion = radios.split('-')[4];
    radios = document.getElementsByClassName(radios);
    for (let r of radios) {
      var numeroLogo = r.id.split('-')[5];
      if(r.checked) {
        r.parentNode.classList.add('ta-seleccionado');
        validarLogos();
      } else { 
        if (document.getElementById('logo-'+numeroArticulo+'-'+numeroTrabajo+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+numeroLogo)) {
          r.parentNode.classList.remove('ta-seleccionado');
        }  
      }
    } 
  }

  function desplegable(elemento) {
    var numeroArticulo = elemento.split('-')[1];
    if(document.getElementById(elemento).classList.contains('trabajos-oculto')) {
      document.getElementById(elemento).classList.remove('trabajos-oculto');
      document.getElementById('flecha-'+numeroArticulo).classList.remove('flecha-invertida');
    } else {
      document.getElementById(elemento).classList.add('trabajos-oculto');
      document.getElementById('flecha-'+numeroArticulo).classList.add('flecha-invertida');
    }
  }

  function updateImage(id, logo) {
    id = id.split('-')[5];
    console.log(logo)
    var img = document.getElementById(logo);
    var logo = $logos_encoded;
    for (var i = 0; i < logo.length; i++) {
        if (logo[i].id == id) {
            console.log(logo[i].img);
            console.log(img);
            img.src = '.' + logo[i].img;
            img.alt = '.' + logo[i].img;
            break;
        }
    }
  }

</script>
  <div id='pagina'>
    <form id='formulario' action='resultado.php' method='post'>";
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
  function validarAr() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const marticulo = document.getElementsByClassName('articulo');

    // Por cada menu de posiciones...
    for (let ma of marticulo) {
      let valido = false;
      const id = ma.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('articulo-' + id[1]);
      });

      const divPosicion = document.getElementById(ma.id);

      // Si hay un mensaje de error, lo borramos
      if (divPosicion.querySelector('ar')) {
        divPosicion.querySelector('ar').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          valido = true;
        }
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + ma.id + " está completo.");
      } else {
        console.log("El menú con id " + ma.id + " está incompleto.");
        // Si no hay mensajes de error, añadimos uno
        if (!divPosicion.querySelector('ar')) {
          let msg = document.createElement('ar');
          msg.innerHTML = "<p>Error: Debe seleccionar al menos una opción</p>";
          divPosicion.appendChild(msg);
        }
      }
    }
  }

  function validarTra() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const marticulo = document.getElementsByClassName('trabajos');

    // Por cada menu de posiciones...
    for (let mt of marticulo) {
      let valido = false;
      const id = mt.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('trabajo-' + id[1]);
      });

      const divPosicion = document.getElementById(mt.id);

      // Si hay un mensaje de error, lo borramos
      if (divPosicion.querySelector('tra')) {
        divPosicion.querySelector('tra').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          valido = true;
        }
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + mt.id + " está completo.");
      } else {
        console.log("El menú con id " + mt.id + " está incompleto.");
        // Si no hay mensajes de error, añadimos uno
        if (!divPosicion.querySelector('tra')) {
          let msg = document.createElement('tra');
          msg.innerHTML = "<p>Error: Debe seleccionar al menos una opción</p>";
          divPosicion.appendChild(msg);
        }
      }
    }
  }

  function validarTar() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mtarticulo = document.getElementsByClassName('tipoArticulo');

    // Por cada menu de posiciones...
    for (let mta of mtarticulo) {
      let valido = false;
      const id = mta.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('tipoArticulo-' + id[1] + '-' + id[2]);
      });

      const divPosicion = document.getElementById(mta.id);

      // Si hay un mensaje de error, lo borramos
      if (divPosicion.querySelector('tar')) {
        divPosicion.querySelector('tar').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          valido = true;
        }
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + mta.id + " está completo.");
      } else {
        console.log("El menú con id " + mta.id + " está incompleto.");
        // Si no hay mensajes de error, añadimos uno
        if (!divPosicion.querySelector('tar')) {
          let msg = document.createElement('tar');
          msg.innerHTML = "<p>Error: Debe seleccionar una opción</p>";
          divPosicion.appendChild(msg);
        }
      }
    }
  }

  function validarPos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mposiciones = document.getElementsByClassName('posicion');

    // Por cada menu de posiciones...
    for (let mp of mposiciones) {
      let valido = false;
      const id = mp.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('posicion-' + id[1] + '-' + id[2] + '-' + id[3]);
      });

      const divPosicion = document.getElementById(mp.id);

      // Si hay un mensaje de error, lo borramos
      if (divPosicion.querySelector('pos')) {
        divPosicion.querySelector('pos').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          valido = true;
        }
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + mp.id + " está completo.");
      } else {
        console.log("El menú con id " + mp.id + " está incompleto.");
        // Si no hay mensajes de error, añadimos uno
        if (!divPosicion.querySelector('pos')) {
          let msg = document.createElement('pos');
          msg.innerHTML = "<p>Error: Debe seleccionar al menos una opción</p>";
          divPosicion.appendChild(msg);
        }
      }
    }
  }


  function validarLogos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mlogos = document.getElementsByClassName('logos');

    // Por cada menu de posiciones...
    for (let ml of mlogos) {
      let valido = false;
      const id = ml.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('logo-' + id[1] + '-' + id[2] + '-' + id[3] + '-' + id[4]);
      });

      const divPosicion = document.getElementById(ml.id);

      // Si hay un mensaje de error, lo borramos
      if (divPosicion.querySelector('logos')) {
        divPosicion.querySelector('logos').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          valido = true;
        }
      }

      // Si el formulario es válido, te lo indico
      if (valido) {
        console.log("El menú con id " + ml.id + " está completo.");
      } else {
        console.log("El menú con id " + ml.id + " está incompleto.");
        // Si no hay mensajes de error, añadimos uno
        if (!divPosicion.querySelector('logos')) {
          let msg = document.createElement('logos');
          msg.innerHTML = "<p>Error: Debe seleccionar una opción</p>";
          divPosicion.appendChild(msg);
        }
      }
    }
  }

  function validar() {
    validarAr();
    validarTra();
    validarTar();
    validarPos();
    validarLogos();
  }

  const boton = document.getElementById('validar');

  boton.addEventListener('click', function(evento) {
    evento.preventDefault();
    validar();
  });
</script>