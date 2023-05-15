<?php
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
  <link rel='stylesheet' href='styles3.css'>
</head>
<body onload='validar();'>
";

include "script.php";
include "validar.php";

echo "
  <div id='pagina'>
    <form id='formulario' action='resultado.php' method='post'>
      $divPedidos
      <div id='observaciones'>
        <h1>Observaciones</h1>
        <textarea id='observaciones2' name='observaciones' onchange='validar()' placeholder='Escriba aquí otras demandas'></textarea>
      </div>
      <input type='submit' id='enviar' disabled>
      <input type='text' id='numero_pedido' class='input-hidden' name='numero_pedido' value=''>
      <input type='text' id='numero_boceto' class='input-hidden' name='numero_boceto' value=''>
    </form>
  </div>
  <div id='listaCheck'>
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
