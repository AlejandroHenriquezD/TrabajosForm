<?php
include_once "c:/xampp/htdocs/centraluniformes/BDReal/numTienda.php";
// header("Cache-Control: no-store, no-cache, must-revalidate");
// header("Pragma: no-cache");
echo "
<a id='volver' href='javascript:history.back()'>Volver</a>
<img id='logoCabecera' src='../../login/cu.png' alt=''/>
<div id='datosTienda'>
  <div>
    <p class='tituloDatos'>Número de tienda:<p>
    <p>$tienda<p>
  </div>
  <div>
    <p class='tituloDatos'>Nombre sede:<p>
    <p>$nombre<p>
  </div>
</div>
";
?>