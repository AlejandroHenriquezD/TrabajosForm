
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


    // Inputs de articulos del formulario
    <?php
    $tiposArticulos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_articulos"), true);
    // $html = ob_get_contents();
    // $dom = new DOMDocument();
    // $dom->loadHTML($html);
    // $finder = new DomXPath($dom);
    // $numeroTrabajos = $finder->query("//*[contains(@class, 'form-controlx')]")->length;
    // echo $html;
    $articulos = "<div id='articulo'><hr>Tipo de artículo: <br>";
    for ($p = 0; $p < count($tiposArticulos); $p++) {
      $articulos .= "<div id='form-controlx-$p'>";
      $articulos .= "<input type='checkbox' id=\"articulo-{$tiposArticulos[$p]['id']}\" name={{$tiposArticulos[$p]['nombre']}} value={{$tiposArticulos[$p]['nombre']}} onclick='mostrarPosicion(\"form-controlx-$p\")'>";
      $articulos .= "<label for={{$tiposArticulos[$p]['id']}}>" . $tiposArticulos[$p]['nombre'] . "</label><br>";
      $articulos .= "</div>";
    }
    $articulos .= "<hr></div>";
    ?>
    var articulo1 = <?php echo json_encode($articulos); ?>;

    articulo1 = elementFromHtml(articulo1);
    //   const articulo1 = elementFromHtml(`
    //   <div id="articulo">
    //     <hr>
    //     Tipo de artículo: <br>
    //     <div id="form-control2-1">
    //       <input type="checkbox" id="camisa" name="camisa" value="Camisa" onclick='mostrarPosicion("form-control2-1")'>
    //       <label for="pechoIzquierdo">Camisa</label><br>
    //     </div>
    //     <div id="form-control2-2">
    //       <input type="checkbox" id="camiseta" name="camiseta" value="Camiseta" onclick='mostrarPosicion("form-control2-2")'>
    //       <label for="pechoDerecho">Camiseta</label><br>
    //     </div>
    //     <div id="form-control2-3">
    //       <input type="checkbox" id="polo" name="polo" value="Polo" onclick='mostrarPosicion("form-control2-3")'>
    //       <label for="polo">Polo</label><br>
    //     </div>
    //     <hr>
    //   </div>
    // `)
    console.log(articulo1);
    const articulo2 = articulo1.cloneNode(true);
    articulo2.querySelector("#form-control2-1").setAttribute("id", "form-control3-1");
    articulo2.querySelector("#form-control2-2").setAttribute("id", "form-control3-2");
    articulo2.querySelector("#form-control2-3").setAttribute("id", "form-control3-3");
    articulo2.querySelector("#camisa").setAttribute("onclick", 'mostrarPosicion("form-control3-1")');
    articulo2.querySelector("#camiseta").setAttribute("onclick", 'mostrarPosicion("form-control3-2")');
    articulo2.querySelector("#polo").setAttribute("onclick", 'mostrarPosicion("form-control3-3")');
    const articulo3 = articulo1.cloneNode(true);
    articulo3.querySelector("#form-control2-1").setAttribute("id", "form-control4-1");
    articulo3.querySelector("#form-control2-2").setAttribute("id", "form-control4-2");
    articulo3.querySelector("#form-control2-3").setAttribute("id", "form-control4-3");
    articulo3.querySelector("#camisa").setAttribute("onclick", 'mostrarPosicion("form-control4-1")');
    articulo3.querySelector("#camiseta").setAttribute("onclick", 'mostrarPosicion("form-control4-2")');
    articulo3.querySelector("#polo").setAttribute("onclick", 'mostrarPosicion("form-control4-3")');

    /* 
      Función que muestra estos inputs al seleccionar
      un checkbox
    */
    function mostrarArticulo(elemento) {
      switch (elemento) {
        case "form-control0":
          if (document.getElementById("trabajo-1").checked) {
            document.getElementById(elemento).appendChild(articulo1);
          } else {
            document.getElementById(elemento).removeChild(articulo1);
          }
          break;
        case "form-control1":
          if (document.getElementById("trabajo-2").checked) {
            document.getElementById(elemento).appendChild(articulo2);
          } else {
            document.getElementById(elemento).removeChild(articulo2);
          }
          break;
        case "form-control2":
          if (document.getElementById("trabajo-3").checked) {
            document.getElementById(elemento).appendChild(articulo3);
          } else {
            document.getElementById(elemento).removeChild(articulo3);
          }
          break;
      }
    }

    // Inputs de posiciones del formulario
    const posicion = []
    posicion[0] = elementFromHtml(`
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
    for (var i = 1; i < 9; i++) {
      posicion[i] = posicion[0].cloneNode(true);
    }

    /* 
      Función que muestra estos inputs al seleccionar
      un checkbox
    */
    function mostrarPosicion(elemento) {
      switch (elemento) {
        case "form-control2-1":
          if (document.getElementById("camisa").checked) {
            document.getElementById(elemento).appendChild(posicion[0]);
          } else {
            document.getElementById(elemento).removeChild(posicion[0]);
          }
          break;
        case "form-control3-1":
          if (document.getElementById("camisa").checked) {
            document.getElementById(elemento).appendChild(posicion[1]);
          } else {
            document.getElementById(elemento).removeChild(posicion[1]);
          }
          break;
        case "form-control4-1":
          if (document.getElementById("camisa").checked) {
            document.getElementById(elemento).appendChild(posicion[2]);
          } else {
            document.getElementById(elemento).removeChild(posicion[2]);
          }
          break;
        case "form-control2-2":
          if (document.getElementById("camiseta").checked) {
            document.getElementById(elemento).appendChild(posicion[3]);
          } else {
            document.getElementById(elemento).removeChild(posicion[3]);
          }
          break;
        case "form-control3-2":
          if (document.getElementById("camiseta").checked) {
            document.getElementById(elemento).appendChild(posicion[4]);
          } else {
            document.getElementById(elemento).removeChild(posicion[4]);
          }
          break;
        case "form-control4-2":
          if (document.getElementById("camiseta").checked) {
            document.getElementById(elemento).appendChild(posicion[5]);
          } else {
            document.getElementById(elemento).removeChild(posicion[5]);
          }
          break;
        case "form-control2-3":
          if (document.getElementById("polo").checked) {
            document.getElementById(elemento).appendChild(posicion[6]);
          } else {
            document.getElementById(elemento).removeChild(posicion[6]);
          }
          break;
        case "form-control3-3":
          if (document.getElementById("polo").checked) {
            document.getElementById(elemento).appendChild(posicion[7]);
          } else {
            document.getElementById(elemento).removeChild(posicion[7]);
          }
          break;
        case "form-control4-3":
          if (document.getElementById("polo").checked) {
            document.getElementById(elemento).appendChild(posicion[8]);
          } else {
            document.getElementById(elemento).removeChild(posicion[8]);
          }
          break;
      }
    }
  </script>

  <form action="resultado.php" method="post">
    <div class="trabajo">
      <hr>
      Tipo de trabajo: <br>
      <!-- <div id="form-control1-1">
        <input type="checkbox" id="serigrafiado" name="serigrafiado" value="Serigrafiado" onclick='mostrarArticulo("form-control1-1")'>
        <label for="serigrafiado">Serigrafiado</label><br>
      </div>
      <div id="form-control1-2">
        <input type="checkbox" id="bordado" name="bordado" value="Bordado" onclick='mostrarArticulo("form-control1-2")'>
        <label for="bordado">Bordado</label><br>
      </div>
      <div id="form-control1-3">
        <input type="checkbox" id="impresion" name="impresion" value="Impresión digital" onclick='mostrarArticulo("form-control1-3")'>
        <label for="impresion">Impresión digital</label><br>
      </div> -->
      <?php
      $tiposTrabajos = json_decode(file_get_contents("http://localhost/trabajosform/tipo_trabajos"), true);
      $trabajos = "";
      for ($p = 0; $p < count($tiposTrabajos); $p++) {
        $trabajos .= "<div id='form-control$p' classname='form-controlx'>";
        $trabajos .= "<input type='checkbox' id=\"trabajo-{$tiposTrabajos[$p]['id']}\" name={{$tiposTrabajos[$p]['nombre']}} value={{$tiposTrabajos[$p]['nombre']}} onclick='mostrarArticulo(\"form-control$p\")'>";
        $trabajos .= "<label for={{$tiposTrabajos[$p]['id']}}>" . $tiposTrabajos[$p]['nombre'] . "</label><br>";
        $trabajos .= "</div>";
      }
      $numeroTrabajos = $p;
      echo $trabajos;
      ?>
      <hr>
    </div>
    Logo: <input type="text" name="logo"><br>
    <input type="submit">
  </form>
</body>

</html>