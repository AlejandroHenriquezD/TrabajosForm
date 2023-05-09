<script>
  function validarAr() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const marticulo = document.getElementsByClassName('articulo');

    // Se coge la lista lateral de artículos
    const listaCheck = document.getElementById('listaCheck');

    // Borramos todos los artículos de la lista de checks
    for (let msgArt of document.querySelectorAll('.msg-art')) {
      msgArt.remove();
    }

    // Por cada menu de posiciones...
    for (let ma of marticulo) {
      let valido = false;
      const id = ma.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('articulo');
      });

      // Si hay un mensaje de error, lo borramos
      if (listaCheck.querySelector('ar')) {
        listaCheck.querySelector('ar').remove();
      }

      // Se recorre dichos checkboxes
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        valido = true;
        // Añadimos los articulos a la lista de checks
        let msgArt = elementFromHtml("<div class='msg-art' id='msg-art-" + inf.id.split('-')[1] + "-" + inf.id.split('-')[2].replaceAll(" ", "") + "'><div class='msg-div'><p id='msg-art-titulo'>" + inf.value + "</p><img id='msg-img-" + inf.id.split('-')[1] + "' src='./img/cancelar.png' alt=''/></div></div>");
        listaCheck.appendChild(msgArt);
      }

      // Si el formulario es válido, te lo indico
      if (!valido && !listaCheck.querySelector('ar')) {
        let msg = document.createElement('ar');
        msg.innerHTML = "<p>Este pedido no tiene artículos asociados</p>";
        listaCheck.appendChild(msg);
      }
    }

    if (listaCheck.querySelector('msg-ped')) {
      listaCheck.removeChild(listaCheck.querySelector('msg-ped'));
    }

    if (listaCheck.querySelectorAll('.msg-art').length === 0 && document.getElementById('selectPedido').value == "pedidoDefault") {
      let msg = document.createElement('msg-ped')
      msg.innerHTML = "<p>Seleccione un pedido</p>";
      listaCheck.appendChild(msg);
    }
  }

  function validarTar() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mtrabajo = document.getElementsByClassName('tipoArticulo');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    // for (let tar of document.querySelectorAll('.tar')) {
    //   tar.remove();
    // }

    // Por cada menu de posiciones...
    for (let mta of mtrabajo) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }

      const id = mta.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('tipoArticulo-' + id[1] + "-" + id[2]);
      });

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) {
        if (!valido[i] && msgArt[i].id === 'msg-art-' + id[1] + "-" + id[2] && !msgArt[i].querySelector('#tar-' + msgArt[i].id.split('-')[2]) && document.getElementById('tipoArticulos-' + id[1] + "-" + id[2])) {
          let msg = elementFromHtml("<div class='tar' id='tar-" + msgArt[i].id.split('-')[2] + msgArt[i].id.split('-')[3] + "'><p>Seleccione un tipo de artículo</p></div>");
          msgArt[i].appendChild(msg);
        }
      }
    }
  }

  function validarTra() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mtrabajo = document.getElementsByClassName('trabajos');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let tra of document.querySelectorAll('.tra')) {
      tra.remove();
    }

    // Por cada menu de posiciones...
    for (let mt of mtrabajo) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }

      const id = mt.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('trabajo-' + id[1] + '-' + id[2] + '-' + id[3]);
      });

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {

            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico

      if (inputsFiltrados.length > 0) {
        for (var i = 0; i < valido.length; i++) {
          if (!valido[i] && msgArt[i].id === 'msg-art-' + id[1] + "-" + id[2] && !msgArt[i].querySelector('#tra-' + msgArt[i].id.split('-')[2]) && document.getElementById("trabajos-" + id[1] + "-" + id[2] + "-" + id[3])) {
            let msg = elementFromHtml("<div class='tra' id='tra-" + msgArt[i].id.split('-')[2] + "-" + msgArt[i].id.split('-')[3] + "'><p>Seleccione un trabajo</p></div>");
            msgArt[i].appendChild(msg);
          }
        }
      }
    }
  }

  function validarPos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mposicion = document.getElementsByClassName('posicion');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let pos of document.querySelectorAll('.pos')) {
      pos.remove();
    }

    // Por cada menu de posiciones...
    for (let mp of mposicion) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }
      const id = mp.id.split('-');
      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('posicion-' + id[1] + '-' + id[2] + '-' + id[3] + '-' + id[4]);
      });

      // Se recorre dichos checkboxes
      var inputsValidos = [];
      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }

      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) { // Se está recorriendo todos los mensajes de articulos
        var codigos = msgArt[i].id.split('-')[2]
        var codigos = CSS.escape(codigos)
        if (!valido[i] && msgArt[i].id === 'msg-art-' + id[1] + "-" + id[2] && !msgArt[i].querySelector('#pos-' + msgArt[i].id.split('-')[2]) && document.getElementById("posicion-" + id[1] + "-" + id[2] + "-" + id[3] + "-" + id[4])) {
          let msg = elementFromHtml("<div class='pos' id='pos-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione una posición</p></div>");
          msgArt[i].appendChild(msg);
        }
      }

    }
  }

  function validarLogos() {
    // Se cogen todos los inputs
    const inputs = document.getElementsByTagName('input');

    // Se cogen los diferentes menus de articulos
    const mlogos = document.getElementsByClassName('logos');

    // Hay que asignar valor a la constante valido
    var msgArt = document.querySelectorAll('.msg-art')

    // Borramos todos los artículos de la lista de checks
    for (let log of document.querySelectorAll('.log')) {
      log.remove();
    }

    // Por cada menu de posiciones...
    for (let ml of mlogos) {
      let valido = [];
      for (var i = 0; i < msgArt.length; i++) {
        valido[i] = false;
      }
      const id = ml.id.split('-');

      // Se recogen todos sus checkboxes
      const inputsFiltrados = Array.from(inputs).filter(input => {
        return input.id.includes('logo-' + id[3] + '-' + id[4] + '-' + id[5] + '-' + id[6] + '-' + id[7]);
      });

      // Se recorre dichos checkboxes
      var inputsValidos = [];

      for (let inf of inputsFiltrados) {
        // Si hay al menos uno seleccionado se da por válido
        if (inf.checked) {
          var i = 0;
          for (let a of msgArt) {
            if (a.id.split('-')[2] === inf.id.split('-')[1]) {
              valido[i] = true;
            }
            i++;
          }
        }
      }
      // Si el formulario es válido, te lo indico
      for (var i = 0; i < valido.length; i++) {
        corta = ""
        larga = ""
        a = 0
        for (let i of id) {
          if (a < 3) {
            larga += i
          } else {
            larga += i
            corta += i
          }
          if (a != 7) {
            larga += "-";
            if (a >= 3) {
              corta += "-";
            }
          }
          a++;
        }
        inputLogo = document.getElementById("logo-" + corta + "-logotipo")
        logoDefecto = document.getElementById("logo-" + corta + "-0")
        if (!valido[i] && (msgArt[i].id.split('-')[2] + "-" + msgArt[i].id.split('-')[3]).replaceAll(" ", "") == id[3] + "-" + id[4] && !msgArt[i].querySelector('#log-' + msgArt[i].id.split('-')[2]) && document.getElementById("msg-art-" + id[3] + '-' + id[4]).childElementCount === 1) {
          let msg = elementFromHtml("<div class='log' id='log-" + msgArt[i].id.split('-')[2] + "'><p>Seleccione un logo</p></div>");
          msgArt[i].appendChild(msg);
        }
        if (logoDefecto.checked && !inputLogo) {
          console.log("crea el input")

          // Crear el elemento de entrada de texto
          let CreLog = elementFromHtml("<input type='text' onchange='validar()' placeholder='Indique aquí el texto con el que quiere trabajar' class='log' id='logo-" + corta + "-logotipo'><p>Seleccione un logo</p></div>");

          // Obtener el elemento al que deseas agregar el elemento de entrada de texto

          var container = document.getElementById(larga);

          // Agregar el elemento de entrada de texto al contenedor utilizando appendChild
          container.appendChild(CreLog);
        }
        inputLogo = document.getElementById("logo-" + corta + "-logotipo")
        if (inputLogo != null && inputLogo.value === "" && document.getElementById("msg-art-" + id[3] + '-' + id[4]).childElementCount === 1) {

          let msg = elementFromHtml("<div class='log' id='log-" + msgArt[i].id.split('-')[2] + "'><p>Introduzca el texto que quiere</p></div>");
          msgArt[i].appendChild(msg);

        }
        if (inputLogo != null && !logoDefecto.checked) {
          console.log("Borra el input")
        }
      }
    }
  }

  function validarTodo() {

    var msgArt = document.querySelectorAll('.msg-art');
    for (m of msgArt) {
      var id = m.id.split('-')[2];
      var elemento = document.getElementById(m.id)
      var elementoHijo = elemento.children[0];
      var elementoNieto = elementoHijo.children[0];
      var nombreAr = elementoNieto.textContent;
      var cb = document.getElementById("articulo-" + id + "-" + nombreAr)

      if (m.childNodes.length <= 1 && cb.checked) {
        m.getElementsByTagName('img')[0].src = './img/aceptar.png';
        m.classList.add('msg-art-verde');
      } else {
        m.getElementsByTagName('img')[0].src = './img/cancelar.png';
        m.classList.remove('msg-art-verde');
      }
    }
  }

  function boton() {
    let button = document.querySelector("#enviar");

    let msgArt = document.querySelectorAll('.msg-art');

    let listaCheck = document.querySelector("#listaCheck");
    button.disabled = false;

    if (listaCheck.contains(document.querySelector('ar')) || listaCheck.contains(document.querySelector('msg-ped'))) {
      button.disabled = true;
    }
    for (m of msgArt) {
      if (!m.classList.contains('msg-art-verde')) {
        button.disabled = true;
      }
    }
  }

  function disablePedidos() {
    var miSelect = document.getElementById('selectPedido'); // Obtener el elemento <select>
    var opcion = miSelect.querySelector('#pedidoDefault'); // Obtener la segunda opción
    if (opcion != null) {
      opcion.remove(); // Deshabilitar la opción
    }
  }

  function validar() {
    validarAr();
    validarTar();
    validarTra();
    validarPos();
    validarLogos();
    validarTodo();
    boton();
  }
</script>