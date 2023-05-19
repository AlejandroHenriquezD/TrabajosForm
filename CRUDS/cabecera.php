<?php
include_once "../../BDReal/numTienda.php";

if(isset($_SESSION["Volver"])) {
  echo "
  <script>
    function volver() {
      window.location = '" . $_SESSION["Volver"] . "';
    }
  </script>
  ";
  echo "<button id='volver' onclick='volver()'><p>Volver</p><ion-icon name='arrow-back-outline'></ion-icon></a></button>";
  unset($_SESSION["Volver"]);
}

echo "
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
<script type='module' src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js'></script>
<script nomodule src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js'></script>
";
?>