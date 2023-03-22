<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
  <link rel="shortcut icon" href="favicon.png">
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<script>
  /* 
    Esta función transforma código html en una
    constante
  */
  function elementFromHtml(html) {
    const template = document.createElement("template");

    template.innerHTML = html.trim();

    return template.content.firstElementChild;
  }

   
  // Inputs de trabajos del formulario
  const algo = 1;
  const trabajo1 = elementFromHtml(`
    <div class="trabajo">
      <hr>
      Tipo de trabajo: <br>
      <div id="form-control4">
        <input type="checkbox" id="serigrafiado" name="serigrafiado" value="Serigrafiado" onclick='mostrarPosicion("form-control4")'>
        <label for="serigrafiado">Serigrafiado</label><br>
      </div>
      <div id="form-control5">
        <input type="checkbox" id="bordado" name="bordado" value="Bordado" onclick='mostrarPosicion("form-control5")'>
        <label for="bordado">Bordado</label><br>
      </div>
      <div id="form-control6">
        <input type="checkbox" id="impresion" name="impresion" value="Impresión digital" onclick='mostrarPosicion("form-control6")'>
        <label for="impresion">Impresión digital</label><br>
      </div>
      <hr>
    </div>
  `)
  const trabajo2 = trabajo1.cloneNode(true);
  const trabajo3 = trabajo1.cloneNode(true);

  /* 
    Función que muestra estos inputs al seleccionar
    un checkbox
  */
  function mostrarTrabajo(elemento) {
    switch (elemento) {
      case "form-control1":
        if(document.getElementById("camisa").checked) {
          document.getElementById(elemento).appendChild(trabajo1);
        } else {
          document.getElementById(elemento).removeChild(trabajo1);
        }
        break;
      case "form-control2":
        if(document.getElementById("camiseta").checked) {
          document.getElementById(elemento).appendChild(trabajo2);
        } else {
          document.getElementById(elemento).removeChild(trabajo2);
        }
        break;
      case "form-control3":
        if(document.getElementById("polo").checked) {
          document.getElementById(elemento).appendChild(trabajo3);
        } else {
          document.getElementById(elemento).removeChild(trabajo3);
        }
        break;
    } 
  }

  // Inputs de posiciones del formulario
  const posicion1 = elementFromHtml(`
    <div class="posicion">
      <hr>
      Posición: <br>
      <input type="checkbox" id="pechoIzquierdo" name="pechoIzquierdo" value="Pecho Izquierdo">
      <label for="pechoIzquierdo">Pecho Izquierdo</label><br>
      <input type="checkbox" id="pechoDerecho" name="pechoDerecho" value="Pecho Derecho">
      <label for="pechoDerecho">Pecho Derecho</label><br>
      <input type="checkbox" id="espalda" name="espalda" value="Espalda">
      <label for="espalda">Espalda</label><br>
      <hr>
    </div>
  `)
  const posicion2 = posicion1.cloneNode(true);
  const posicion3 = posicion1.cloneNode(true);

  /* 
    Función que muestra estos inputs al seleccionar
    un checkbox
  */
  function mostrarPosicion(elemento) {
    switch (elemento) {
      case "form-control4":
        if(document.getElementById("serigrafiado").checked) {
          document.getElementById(elemento).appendChild(posicion1);
        } else {
          document.getElementById(elemento).removeChild(posicion1);
        }
        break;
      case "form-control5":
        if(document.getElementById("bordado").checked) {
          document.getElementById(elemento).appendChild(posicion2);
        } else {
          document.getElementById(elemento).removeChild(posicion2);
        }
        break;
      case "form-control6":
        if(document.getElementById("impresion").checked) {
          document.getElementById(elemento).appendChild(posicion3);
        } else {
          document.getElementById(elemento).removeChild(posicion3);
        }
        break;
    } 
  } 
  </script>

  <form action="resultado.php" method="post">
    <div id="articulo">
      Tipo de artículo: <br>
      <div id="form-control1">
        <input type="checkbox" id="camisa" name="camisa" value="Camisa" onclick='mostrarTrabajo("form-control1")'>
        <label for="pechoIzquierdo">Camisa</label><br>
      </div>
      <div id="form-control2">
        <input type="checkbox" id="camiseta" name="camiseta" value="Camiseta" onclick='mostrarTrabajo("form-control2")'>
        <label for="pechoDerecho">Camiseta</label><br>
      </div>
      <div id="form-control3">
          <input type="checkbox" id="polo" name="polo" value="Polo" onclick='mostrarTrabajo("form-control3")'>
          <label for="polo">Polo</label><br>
      </div>
    </div>
    Logo: <input type="text" name="logo"><br>
    <input type="submit">
  </form>
</body>
</html>