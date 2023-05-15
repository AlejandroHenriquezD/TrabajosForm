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
  var desplegables = $desplegables;
  var trabajos = $arrayTrabajos;
  var posiciones = $arrayPosiciones;
  var logos = $arrayLogos;
  
  for (var i = 0; i < articulos.length; i++) {
    articulos[i] = elementFromHtml(articulos[i]);
    bocetos[i] = elementFromHtml(bocetos[i]);
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
    var bocetoAntiguo = obtenerElemento(bocetos, 'boceto-'+elementoActual);
    var articuloAntiguo = obtenerElemento(articulos, 'articulos-'+elementoActual);
    if(elementoActual != null) {
      document.getElementById('listaCheck').removeChild(bocetoAntiguo);
      document.getElementById('pedidos').removeChild(articuloAntiguo);
      // document.getElementById('pedidos').removeChild(document.getElementById('div-cli'));
    }

    var divpdf = document.createElement('div');
    // var divcli = document.createElement('div');
    // divcli.id = 'div-cli';
    divpdf.id = 'div-pdf';
    if(document.getElementById('selectPedido').value != '') {
      elementoActual = document.getElementById('selectPedido').value;

      var seriePedido = elementoActual.split('-')[0];
      var numeroPedido = elementoActual.split('-')[1];

      var boceto = obtenerElemento(bocetos, 'boceto-'+seriePedido+'-'+numeroPedido);
      var articulo = obtenerElemento(articulos, 'articulos-'+document.getElementById('selectPedido').value);
      document.getElementById('listaCheck').appendChild(boceto);
      // document.getElementById('pedidos').appendChild(divcli);
      if(document.getElementById('selectPedido').value != 'pedidoDefault'){
      document.getElementById('pedidos').appendChild(articulo);
      }
      if(!document.getElementById('div-pdf')){
      document.getElementsByClassName('div-boceto')[0].appendChild(divpdf);
      }

      // elementoActual = pedido['id'];
      
      var combo = document.getElementById('selectPedido');
      var ejercicio = combo.options[combo.selectedIndex].text;
      
      document.getElementById('numero_pedido').value = ejercicio;
    } else {
      elementoActual = null;
    }
    updatePdf();
    validar();
    disablePedidos();
  }

  function mostrarTiposArticulos(elemento) {

    var divElemento = document.getElementById(elemento);
    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    descripcion = CSS.escape(descripcion.replaceAll(' ', ''));

    var tipoArticulo = tipoArticulos.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);
    tipoArticulo = elementFromHtml(tipoArticulo);

    var desplegable = desplegables.replaceAll('tipo', 'tipoArticulos');
    desplegable = desplegable.replaceAll('codigos', numeroArticulo+'-'+descripcion);
    desplegable = elementFromHtml(desplegable);

    if (divElemento.querySelector(':first-child').querySelector(':first-child').checked) {
      divElemento.appendChild(tipoArticulo);
      divElemento.appendChild(desplegable); 
    } else {
      divElemento.removeChild(divElemento.querySelector('div'));
      divElemento.removeChild(divElemento.querySelector('.desplegable'));
    }
    validar();
  }

  function mostrarPosiciones(elemento) {
    
    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulos = elemento.split('-')[4];
    descripcion = CSS.escape(descripcion);

    var posicion = null;
    for(pos of posiciones){

      if(pos.includes('posicion-codigoArticulo-'+numeroTipoArticulos)){
        posicion = pos;
        break;
      }
    }
    var divTipoArticulos = 'tipoArticulos-'+numeroArticulo+'-'+descripcion;

    var radios = document.getElementsByClassName('articuloRadio-'+numeroArticulo+'-'+descripcion);

    if(posicion != null) {
      for (let r of radios) {
        var numeroTipoArticulo = r.id.split('-')[3];
        var codigos = numeroArticulo+'-'+descripcion;

        if(r.checked) {
          posicion = posicion.replaceAll('codigoArticulo', codigos);
          posicion = elementFromHtml(posicion);

          if(!document.getElementById('posiciones-'+codigos+'-'+numeroTipoArticulo)) {
            document.getElementById(divTipoArticulos).appendChild(posicion);
          }
          r.parentNode.classList.add('ta-seleccionado');
        } else {
          if (document.getElementById('posiciones-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo)) {
            document.getElementById(divTipoArticulos).removeChild(document.getElementById('posiciones-'+codigos+'-'+numeroTipoArticulo));
            r.parentNode.classList.remove('ta-seleccionado');
          }
        }
      }
    }
    validar();
  }

  function mostrarTrabajos(elemento) {

    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroPosicion = elemento.split('-')[5];
    descripcion = CSS.escape(descripcion);

    var cb = document.getElementById('posicion-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion);
    trabajo = trabajos.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);
    trabajo = trabajo.replaceAll('tiposArticulos', numeroTipoArticulo);
    trabajo = trabajo.replaceAll('posiciones', numeroPosicion);
    trabajo = trabajo.replaceAll('nombrePosicion', cb.name);
    trabajo = elementFromHtml(trabajo);

    var desplegable = desplegables.replaceAll('tipo', 'trabajos');
    desplegable = desplegable.replaceAll('codigos', numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion);
    desplegable = elementFromHtml(desplegable);

    var divPosiciones = document.getElementById('posiciones-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo);

    if (cb.checked) {
      divPosiciones.appendChild(trabajo);
      divPosiciones.appendChild(desplegable);
    } else {
      divPosiciones.removeChild(document.getElementById('trabajos-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion));
      divPosiciones.removeChild(document.getElementById('desplegable-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion));
    }
    
    validar()
  }

  function mostrarLogos(elemento) {
    
    var numeroArticulo = elemento.split('-')[2];
    var descripcion = elemento.split('-')[3];
    var numeroTipoArticulo = elemento.split('-')[4];
    var numeroPosicion = elemento.split('-')[5];
    var numeroTrabajo = elemento.split('-')[6];
    descripcion = CSS.escape(descripcion);

    // Buscar contenedor de logos del pedido actual
    var logo = null;
    for(log of logos){
      if(log.includes('logos-'+elementoActual+'-codigoArticulo-idTipoArticulo-idPosicion-idTiposTrabajos')){
        logo = log;
        break;
      }
    }

    var divTrabajos = document.getElementById('trabajos-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion);

    var radios = document.getElementsByClassName('trabajo-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion);

    if(logo != null) {
      logo = logo.replaceAll('codigoArticulo', numeroArticulo+'-'+descripcion);
      logo = logo.replaceAll('idTipoArticulo', numeroTipoArticulo);
      logo = logo.replaceAll('idTiposTrabajos', numeroTrabajo);
      logo = logo.replaceAll('idPosicion', numeroPosicion);
      
      var desplegable = desplegables.replaceAll('tipo', 'logos');
      desplegable = desplegable.replaceAll('codigos', elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+numeroTrabajo);
      desplegable = elementFromHtml(desplegable);
      for (let r of radios) {
        var nTrabajo = r.id.split('-')[5];

        if (r.checked) {
          logo = logo.replaceAll('nombrePosicion', r.parentNode.textContent);
          logo = elementFromHtml(logo);
          if(!document.getElementById('logos-'+elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+nTrabajo)) {
            divTrabajos.appendChild(logo);
            divTrabajos.appendChild(desplegable);
          }
        } else {
          if (document.getElementById('logos-'+elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+nTrabajo)) {
            divTrabajos.removeChild(document.getElementById('logos-'+elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+nTrabajo));
            divTrabajos.removeChild(document.getElementById('desplegable-'+elementoActual+'-'+numeroArticulo+'-'+descripcion+'-'+numeroTipoArticulo+'-'+numeroPosicion+'-'+nTrabajo));
          }
        }
      }
    }
    validar();
  }

  function logoSeleccionado(radios) {
    // var numeroPedido = radios.split('-')[1];
    // var numeroArticulo = radios.split('-')[2];
    // var numeroTipoArticulo = radios.split('-')[3];
    // var numeroTrabajo = radios.split('-')[4];
    // var numeroPosicion = radios.split('-')[5];
    radios = document.getElementsByClassName(CSS.escape(radios));
    for (let r of radios) {
      var numeroLogo = r.id.split('-')[6];
      if(r.checked) {
        r.parentNode.classList.add('ta-seleccionado');
      } else {
        r.parentNode.classList.remove('ta-seleccionado');
      }
    }
    validar()
  }

  function desplegable(elemento) {
    elemento = CSS.escape(elemento);
    var divElemento = document.getElementById(elemento);
    var hijos = divElemento.children;
    var indices = elemento.substring(elemento.indexOf('-'));
    
    if(divElemento.classList.contains('div-oculto')) {
      divElemento.classList.remove('div-oculto');
      document.getElementById('flecha'+indices).classList.remove('flecha-invertida');

      for(hijo of hijos){
        hijo.classList.remove('div-oculto');
      }
    } else {
      divElemento.classList.add('div-oculto');
      document.getElementById('flecha'+indices).classList.add('flecha-invertida');

      for(hijo of hijos){
        hijo.classList.add('div-oculto');
      }
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
    var selectBoceto = document.getElementById('selectBoceto');
    if(selectBoceto != null) {
      var option = selectBoceto.value;
      
      if(selectBoceto.value == 'bocetoDefault') {
        selectBoceto.classList.add('select-default');
        if(selectBoceto.lastChild.id == 'bocetoDefault') {
          pdf = elementFromHtml('<p id=\"pdf\">No existen bocetos para este pedido<p>');
        } else {
          pdf = elementFromHtml('<p id=\"pdf\">Seleccione un boceto<p>');
        }
        divPdf.appendChild(pdf);
      } else {
        selectBoceto.classList.remove('select-default');
        document.getElementById('numero_boceto').value = option;
        var urlBoceto = null;
        for(var p=0; p < pedidos.length; p++) {
          if(pedidos[p]['SeriePedido']+'-'+pedidos[p]['NumeroPedido'] == elementoActual) {
            if(bocetosUrl[p][option]) {
              var urlBoceto = '.' + bocetosUrl[p][option];
              break;
            }
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
