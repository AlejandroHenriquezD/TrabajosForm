<?php

include "rellenar.php";

echo "
<script>
  function elementFromHtml(html) {
    const template = document.createElement('template');

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

  var pedidos = $pedidos;
  var clientes = $clientes;
  var bocetosUrl = $bocetosUrl;
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
    var elemento = array.find(a => a.id === id);
    return elemento;
  }

  function indexPedido() {
    var select = document.getElementById('selectPedido');
    return select.selectedIndex-1;
  }

  function mostrarArticulos() {
    // var bocetoAntiguo = obtenerElemento(bocetos, 'boceto-'+elementoActual);
    var articuloAntiguo = obtenerElemento(articulos, 'articulos-'+elementoActual);
    if(elementoActual != null) {
      // document.getElementById('listaCheck').removeChild(bocetoAntiguo);
      document.getElementById('pedidos').removeChild(articuloAntiguo);
      // document.getElementById('pedidos').removeChild(document.getElementById('div-cli'));
    }

    var divpdf = document.createElement('div');
    // var divcli = document.createElement('div');
    // divcli.id = 'div-cli';
    divpdf.id = 'div-pdf';
    if(document.getElementById('selectPedido').value != '') {
      for(pedido of pedidos) {
        // if(document.getElementById('selectPedido').value == pedido['id']) {
          for(cliente of clientes) {
            if(cliente.id === pedido['id_cliente']){
              // divcli.innerHTML = '<h1 class=\"titulo\">Cliente</h1>';
              // divcli.innerHTML += '<div class=\"datoscli\"><p>Nombre: ' + cliente['nombre'] + '</p><p>Teléfono: ' + cliente['telefono'] + '</p><p>Correo: ' + cliente['correo'] + '</p><p>Dirección: ' + cliente['dirección'] + '</p></div>';
              // divcli.innerHTML += '<div class=\"datoscli\"><p>CIF/NIF: ' + cliente['cif_nif'] + '</p><p>Número de cliente: ' + cliente['numero_cliente'] + '</p><p>Razón social: ' + cliente['razon_social'] + '</p></div>';
            }
          }
          // var boceto = obtenerElemento(bocetos, 'boceto-'+pedido['id']);
          var articulo = obtenerElemento(articulos, 'articulos-'+document.getElementById('selectPedido').value);
          // document.getElementById('listaCheck').appendChild(boceto);
          // document.getElementById('pedidos').appendChild(divcli);
          document.getElementById('pedidos').appendChild(articulo);

          // if(!document.getElementById('div-pdf')){
          // document.getElementsByClassName('boceto')[0].appendChild(divpdf);
          // }

          // elementoActual = pedido['id'];
          elementoActual = document.getElementById('selectPedido').value;
          document.getElementById('numero_pedido').value = elementoActual;
          
          break;
        // } else {
        //   elementoActual = null;
        // }
      }
    } else {
      elementoActual = null;
    }
    updatePdf();
    validar();
  }

  function mostrarTiposArticulos(elemento) {

    var divElemento = document.getElementById(elemento);
    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    descripcion = descripcion.replaceAll(' ', '')

    // var desplegable = obtenerElemento(desplegablesTipoArticulos[indexPedido()], 'desplegable-'+numeroArticulo);
    var tipoArticulo = tipoArticulos.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);

    tipoArticulo = elementFromHtml(tipoArticulo);

    if (divElemento.querySelector(':first-child').querySelector(':first-child').checked) {
      divElemento.appendChild(tipoArticulo);
      // divElemento.appendChild(desplegable); 
    } else {
      divElemento.removeChild(divElemento.querySelector('div'));
      // divElemento.removeChild(desplegable);
    }
    validar();
  }

  function mostrarTrabajos(elemento) {
    
    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulos = elemento.split('-')[4];
    
    var divTipoArticulos = 'tipoArticulos-'+numeroArticulo+'-'+descripcion;

    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+descripcion);
    for (let r of radios) {
      var numeroTipoArticulo = r.id.split('-')[3];
      if(r.checked) {
        var trabajo = trabajos.replaceAll('codigoArticulo-tiposArticulos', numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo);
        trabajo = elementFromHtml(trabajo);
        document.getElementById(divTipoArticulos).appendChild(trabajo);
        r.parentNode.classList.add('ta-seleccionado');
      } else {
        if (document.getElementById('trabajos-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo)) { 
          document.getElementById(divTipoArticulos).removeChild(document.getElementById('trabajos-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo));
          r.parentNode.classList.remove('ta-seleccionado');
        }
      }
    } 
    validar()
  }

  function mostrarPosiciones(elemento) {

    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroTrabajo = elemento.split('-')[5];

    // Buscar un substring por cada string del array de posiciones y devolver su posicion
    var posicion = null;
    for(pos of posiciones){
      if(pos.includes('posicion-codigoArticulo-'+numeroTipoArticulo+'-idTiposTrabajos')){
        posicion = pos;
        break;
      }
    }

    if(posicion != null) {
      var cb = document.getElementById('trabajo-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroTrabajo);
      posicion = posicion.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);
      posicion = posicion.replaceAll('idTiposTrabajos', numeroTrabajo);
      posicion = posicion.replaceAll('nombreTiposTrabajos', cb.name);
      posicion = elementFromHtml(posicion);
      var divTrabajos = document.getElementById('trabajos-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo);

      // var desplegable = obtenerElemento(desplegablesPosiciones[indexPedido()][indexTipoArticulo][indexTrabajo], 'desplegable-'+numeroArticulo+'-'+numeroTipoArticulo+'-'+numeroTrabajo);

      if (cb.checked) {
        divTrabajos.appendChild(posicion);
        // divTrabajos.appendChild(desplegable);
      } else {
        divTrabajos.removeChild(document.getElementById('posicion-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroTrabajo));
        // document.getElementById(divTrabajos).removeChild(desplegable);
      }
    }
    validar()
  }

  function mostrarLogos(elemento) {

    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroTrabajo = elemento.split('-')[5];
    var numeroPosicion = elemento.split('-')[6];

    // Buscar contenedor de logos del pedido actual
    var logo = null;
    for(log of logos){
      if(log.includes('logos-'+elementoActual+'-codigoArticulo-idTipoArticulo-idTiposTrabajos-idPosicion')){
        logo = log;
        break;
      }
    }

    if(logo != null) {
      var cb = document.getElementById('posicion-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion);
      logo = logo.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);
      logo = logo.replaceAll('idTipoArticulo', numeroTipoArticulo);
      logo = logo.replaceAll('idTiposTrabajos', numeroTrabajo);
      logo = logo.replaceAll('idPosicion', numeroPosicion);
      logo = logo.replaceAll('nombrePosicion', cb.parentNode.textContent);
      logo = elementFromHtml(logo);
      var divPosiciones = document.getElementById('posicion-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroTrabajo);

      if (cb.checked) {
        divPosiciones.appendChild(logo);
        // divPosiciones.appendChild(desplegable);
      } else {
        divPosiciones.removeChild(document.getElementById('logos-'+elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroTrabajo+'-'+numeroPosicion));
        // divPosiciones.removeChild(desplegable);
      }
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

  function updatePdf() {
    document.getElementById('numero_boceto').value = null;
    var divPdf = document.getElementById('div-pdf');
    var pdf = document.getElementById('pdf');
    if(pdf != null){
      divPdf.removeChild(pdf);
    }

    if(document.getElementById('selectBoceto') != null) {
      var option = document.getElementById('selectBoceto').value;
      
      if(document.getElementById('selectBoceto').value == 'bocetoDefault') {
        if(document.getElementById('selectBoceto').lastChild.id == 'bocetoDefault') {
          pdf = elementFromHtml('<p id=\"pdf\">No existen bocetos para este pedido<p>');
        } else {
          pdf = elementFromHtml('<p id=\"pdf\">Seleccione un boceto<p>');
        }
        divPdf.appendChild(pdf);
      } else {
        document.getElementById('numero_boceto').value = option;
        var urlBoceto = null;
        for(var p=0; p < pedidos.length; p++) {
          if(bocetosUrl[p][option]) {
            var urlBoceto = '.' + bocetosUrl[p][option];
            break;
          }
        }
        
        fetch(urlBoceto)
        .then(response => {
          if (response.ok) {
            pdf = elementFromHtml('<iframe id=\"pdf\" src=\"\" style=\"width:100%; height:100%;\" frameborder=\"0\"></iframe>');
            pdf.src = urlBoceto;
            divPdf.appendChild(pdf);
          } else {
            pdf = elementFromHtml('<p id=\"pdf\">No existe boceto asociado<p>');
            divPdf.appendChild(pdf);
            throw new Error('El archivo no se pudo obtener');
          }
        })
        .catch(error => {
          if (error.message === 'El archivo no se pudo obtener') {
            console.log(error.message);
          } else {
            console.log('Error:', error.message);
          }
        });
      }
    }
  }

</script>
";