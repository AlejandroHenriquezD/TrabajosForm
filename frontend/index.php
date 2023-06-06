<?php
// header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
// header("Expires: Sat, 1 Jul 2000 05:00:00 GMT"); // Fecha en el pasado
// session_start();

echo "
<!DOCTYPE html>
<html lang='en'>
<head>
  <meta charset='UTF-8'>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <title>Nuevo trabajo</title>
  <link rel='shortcut icon' href='./img/favicon.png'>
  <link rel='stylesheet' href='styles.css'>
  <meta http-equiv='Expires' content='0'>
  <meta http-equiv='Last-Modified' content='0'>
  <meta http-equiv='Cache-Control' content='no-cache, mustrevalidate'>
  <meta http-equiv='Pragma' content='no-cache'>
</head>
<body onload='validar();'>
";

include "script.php";
include "validar.php";
include "cabecera.php";

echo "
  <div id='pagina'>
    <form id='formulario' action='resultado.php' method='post'>
      $divPedidos
      <div id='observaciones'>
        <h1>Observaciones</h1>
        <textarea id='observaciones2' name='observaciones' onchange='validar()' placeholder='Escriba aquí otras demandas'></textarea>
      </div>
      <input type='hidden' id='numero_pedido' class='input-hidden' name='numero_pedido' value=''>
      <input type='hidden' id='numero_boceto' class='input-hidden' name='numero_boceto' value=''>
    </form>
    <button id='enviar' onclick='generarOrdenTrabajo()' disabled>Enviar orden de trabajo</button>
  </div>
  <div id='contenido-lateral'>
    <div id='listaCheck'>
  </div>
  </div>
  <div id='fondo-confirmar-accion' class='oculto'>
    <div id='cuadro-confirmar-accion'>
      <p>Orden de trabajo generada</p>
      <button onclick='recargar()'>Aceptar</button>
    </div>
  </div>
";

include "menu.php";

echo "
  <div id='background'>
    <div class='ball' id='greenball1'/>
    <div class='ball' id='greenball2'/>
    <div class='ball' id='greenball3'/>
    <div class='ball' id='redball1'/>
    <div class='ball' id='redball2'/>
    <div class='ball' id='redball3'/>
    <div class='ball' id='blueball1'/>
    <div class='ball' id='blueball2'/>
    <div class='ball' id='yellowball1'/>
  </div>
</body>
</html>";
