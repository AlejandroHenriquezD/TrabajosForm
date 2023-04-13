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
$arrayLogos = array();
$posiciones = array();
$arrayLogos = array();
$relacion = array();
$desplegablesTipoArticulos = array();
$desplegablesPosiciones = array();
$desplegablesLogos = array();
$divPedidos = "<div id='pedidos'><h1>Pedido</h1><select name='selectPedido[]' id='selectPedido' onchange=mostrarArticulos(this.value)>";
for ($o = 0; $o < $numeroPedidos; $o++) {
  $divPedidos .= "<option id={$pedidos[$o]['id']} value={$pedidos[$o]['id']}>{$pedidos[$o]['id']}</option>";
}
$divPedidos .= "</select>";
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
        $arrayArticulos[$o] .= "<input type='checkbox' id=\"articulo-{$articulos[$i]['id']}\" name='articulo[]' value={{$articulos[$i]['descripcion']}} onclick='mostrarTiposArticulos(\"form-control-{$articulos[$i]['id']}\")'>";
        $arrayArticulos[$o] .= "<label for=\"articulo-{$articulos[$i]['id']}\">" . $articulos[$i]['descripcion'] . "</label><br>";
        $arrayArticulos[$o] .= "</div>";
      }
      $arrayTipoArticulos[$o][$i] = "<div class='tipoArticulo' id=\"tipoArticulos-{$articulos[$i]['id']}\">";
      $arrayTipoArticulos[$o][$i] .= "<h1>Tipo de articulo</h1><div class='slider'><div class='coleccion'>";
      for ($a = 0; $a < $numeroTipoArticulos; $a++) {
        $arrayTipoArticulos[$o][$i] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\">";
        $arrayTipoArticulos[$o][$i] .= "<input type='radio' class=\"articuloRadio-{$articulos[$i]['id']}\" id=\"tipoArticulo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\" name=\"articuloRadio-{$articulos[$i]['id']}\" value={{$tiposArticulos[$a]['nombre']}} onclick='mostrarTrabajos(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\")'>";
        $arrayTipoArticulos[$o][$i] .= "<img src=\".{$tiposArticulos[$a]['img']}\" alt=\".{$tiposArticulos[$a]['img']}\"/>";
        $arrayTipoArticulos[$o][$i] .= "<br></div>";
        $trabajos[$o][$i][$a] = "<div class='trabajos' id=\"trabajos-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}\"><h1>Trabajos</h1><div class='coleccionHorizontal'>";
        for ($t = 0; $t < $numeroTrabajos; $t++) {
          $trabajos[$o][$i][$a] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\">";
          $trabajos[$o][$i][$a] .= "<input type='checkbox' id=\"trabajo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\" name={{$tiposTrabajos[$t]['nombre']}} value={{$tiposTrabajos[$t]['nombre']}} onclick='mostrarPosiciones(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\")'>";
          $trabajos[$o][$i][$a] .= "<label for=\"trabajo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\">" . $tiposTrabajos[$t]['nombre'] . "</label><br>";
          $trabajos[$o][$i][$a] .= "</div>";
          $posiciones[$o][$i][$a][$t] = "<div class='posicion' id=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}\"><h1>Posiciones: </h1><div class='coleccionHorizontal'>";
          for ($p = 0; $p < $numeroPosicionesArticulos; $p++) { 
            $arrayLogos[$o][$i][$a][$t][$p] = "<div class='logos' id=\"logos-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
            if ($posicionesArticulos[$p]['id_tipo_articulo'] == $tiposArticulos[$a]['id']) {
              // Obtiene la posición del array donde se encuentra el id de la posición
              $posIndex = array_search($posicionesArticulos[$p]['id_posicion'], array_column($tiposPosiciones, 'id'));
              $posiciones[$o][$i][$a][$t] .= "<div id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">";
              $posiciones[$o][$i][$a][$t] .= "<input type='checkbox' id=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" class='posicion-checkbox' name='posicion-checkbox[]' value=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" onclick='mostrarLogos(\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
              $posiciones[$o][$i][$a][$t] .= "<label for=\"posicion-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\">" . $tiposPosiciones[$posIndex]['descripcion'] . "</label><br>";
              $posiciones[$o][$i][$a][$t] .= "</div>";
              $arrayLogos[$o][$i][$a][$t][$p] .= "<div class='seleccionado'><h1>{$tiposPosiciones[$posIndex]['descripcion']}</h1></div><h1>Logotipo</h1><div class='slider'><div class='coleccion'>";
              for ($l = 0; $l < count($logos); $l++) {
                $arrayLogos[$o][$i][$a][$t][$p] .= "<div class='ta' id=\"form-control-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\">";
                $arrayLogos[$o][$i][$a][$t][$p] .= "<input type='radio' class=\"logoRadio-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\" id=\"logo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" name=\"img-input[grupo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}]\" value=\"logo-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}-{$logos[$l]['id']}\" onclick='logoSeleccionado(\"logoRadio-{$articulos[$i]['id']}-{$tiposArticulos[$a]['id']}-{$tiposTrabajos[$t]['id']}-{$posicionesArticulos[$p]['id_posicion']}\")'>";
                $arrayLogos[$o][$i][$a][$t][$p] .= "<img src=\".{$logos[$l]['img']}\" alt=\".{$logos[$l]['img']}\"/>";
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
  <link rel='stylesheet' href='styles3.css'>
</head>
<body onload='añadirPrimero();'>
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

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

  function mostrarTiposArticulos(elemento) {
    var numeroArticulo = elemento.split('-')[2];

    var desplegable = obtenerElemento(desplegablesTipoArticulos[indexPedido()], 'desplegable-'+numeroArticulo);
    var tipoArticulo = obtenerElemento(tipoArticulos[indexPedido()], 'tipoArticulos-'+numeroArticulo);

    if (document.getElementById('articulo-'+numeroArticulo).checked) {
      document.getElementById(elemento).appendChild(tipoArticulo);
      document.getElementById(elemento).appendChild(desplegable);
      validarAr()     
    } else {
      document.getElementById(elemento).removeChild(tipoArticulo);
      document.getElementById(elemento).appendChild(desplegable);
    }
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
        validarTar();
      } else {
        if (document.getElementById('trabajos-'+numeroArticulo+'-'+numeroTipoArticulo)) { 
          document.getElementById(divTipoArticulos).removeChild(trabajo);
          r.parentNode.classList.remove('ta-seleccionado');
        }
      }
    } 
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
      validarTra()
    } else {
      document.getElementById(divTrabajos).removeChild(document.getElementById('posicion-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo));
      document.getElementById(divTrabajos).removeChild(desplegable);
    }
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
      validarPos()
    } else {
      document.getElementById(divPosiciones).removeChild(document.getElementById('logos-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion));
      document.getElementById(divPosiciones).removeChild(desplegable);
    }
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
        validarLogos();
      } else { 
        if (document.getElementById('logo-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion+'-'+numeroLogo)) {
          r.parentNode.classList.remove('ta-seleccionado');
        }  
      }
    } 
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
      <h1>Observaciones</h1>
      <textarea name='observaciones' placeholder='Escriba aquí otras demandas'></textarea>
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
        return input.id.includes('trabajo-' + id[1] + '-' + id[2]);
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
        return input.id.includes('tipoArticulo-' + id[1]);
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

  boton.addEventListener('click', function (evento) {
    evento.preventDefault();
    validar();
  });
</script>